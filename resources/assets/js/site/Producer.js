import { EventBus } from '../common/event-bus';
import Building from './Building';

export default Building.extend({
    props: ['grid', 'url', 'storeUrl'],

    data() {
        return {
            mined: 0,
            quantity: '',
            planet: {
                resource_id: undefined,
                resources: []
            },
            selected: {
                id: undefined,
                efficiency: 0,
            },
            data: {
                resources: []
            }
        };
    },

    created() {
        EventBus.$on('resource-updated', resource => this.mined = resource);
        EventBus.$on('planet-update', this.fetchData);
        EventBus.$on('planet-updated', planet => this.planet = planet);
    },

    computed: {
        isTransmutable() {
            return this.quantity > 0 && this.quantity <= this.transmutableQuantity;
        },

        transmutableQuantity() {
            if (this.planet.resource_id === this.selected.id) {
                return Math.floor(this.mined);
            }

            return _.find(this.planet.resources, {
                id: this.selected.id
            }).quantity;
        },

        quantityPlaceholder() {
            return `(${this.transmutableQuantity})`;
        },

        transmutableEnergy() {
            return this.isTransmutable
                ? this.quantity * this.selected.efficiency
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

                this.select(
                    _.first(this.data.resources)
                );
            });
        },

        store() {
            axios.post(
                this.storeUrl.replace('__grid__', this.grid.id).replace('__resource__', this.selected.id), {
                    quantity: this.quantity
                }
            );
        },

        isSelected(resource) {
            return this.selected.id === resource.id;
        },

        select(resource) {
            this.selected = resource;
            this.quantity = '';
        }
    }
});
