import { EventBus } from './event-bus';
import Building from './Building';

export default Building.extend({
    props: [
        'grid',
        'url',
        'storeUrl',
        'destroyUrl'
    ],

    data() {
        return {
            isResearch: false,
            energy: 0,
            supply: 0,
            quantity: '',
            selected: {
                id: undefined,
                supply: 0,
                train_cost: 0,
                train_time: 0
            },
            data: {
                remaining: 0,
                units: []
            }
        };
    },

    created() {
        EventBus.$on('energy-updated', energy => this.energy = energy);
        EventBus.$on('planet-update', () => this.fetchData());
        EventBus.$on('planet-updated', planet => this.supply = planet.supply - planet.used_supply - planet.used_training_supply);
    },

    computed: {
        isTrainable() {
            return this.quantity > 0 && this.quantity <= this.trainableQuantity;
        },

        trainableQuantity() {
            return Math.floor(
                Math.min(this.energy / this.selected.train_cost, this.supply / this.selected.supply)
            );
        },

        trainName() {
            return `${this.data.quantity} x ${this.selected.name}`;
        },

        trainTime() {
            return this.isTrainable
                ? this.quantity * this.selected.train_time
                : 0;
        }
    },

    watch: {
        isEnabled() {
            this.fetchData();
        }
    },

    methods: {
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(
                this.url.replace('__grid__', this.grid.id)
            ).then(response => {
                this.data = response.data;
                this.initRemaining(this.data.remaining);

                this.select(
                    _.first(this.data.units)
                );
            });
        },

        store() {
            axios.post(
                this.storeUrl.replace('__grid__', this.grid.id).replace('__unit__', this.selected.id), {
                    quantity: this.quantity
                }
            );
        },

        destroy() {
            axios.delete(
                this.destroyUrl.replace('__grid__', this.grid.id)
            );
        },

        isSelected(unit) {
            return this.selected.id === unit.id;
        },

        select(unit) {
            this.selected = unit;
            this.quantity = '';
        }
    }
});
