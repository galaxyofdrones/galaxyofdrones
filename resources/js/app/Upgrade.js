import { EventBus } from './event-bus';
import Modal from './Modal';
import Producer from './Producer';
import Scout from './Scout';
import Trader from './Trader';
import Trainer from './Trainer';

export default Modal.extend({
    props: [
        'centralType',
        'url',
        'storeUrl',
        'destroyUrl'
    ],

    components: {
        Producer,
        Scout,
        Trader,
        Trainer
    },

    data() {
        return {
            isCapitalPlanet: false,
            energy: 0,
            grid: {
                id: undefined,
                building_id: undefined
            },
            data: {
                hasTraining: false,
                remaining: 0,
                building: {},
                upgrade: {}
            }
        };
    },

    created() {
        EventBus.$on('building-click', this.open);
        EventBus.$on('energy-updated', energy => this.energy = energy);
        EventBus.$on('planet-update', () => this.fetchData());
        EventBus.$on('planet-updated', planet => this.isCapitalPlanet = planet.is_capital);
    },

    computed: {
        canConstruct() {
            return this.energy >= this.data.upgrade.construction_cost;
        },

        canDemolish() {
            return !this.data.hasTraining && !this.remaining && (!this.isCapitalPlanet || this.data.building.type !== this.centralType);
        },

        building() {
            return this.data.building;
        }
    },

    methods: {
        open(grid) {
            this.grid = grid;
            this.fetchData(true);
        },

        openDemolish() {
            this.openAfterHidden(
                () => EventBus.$emit('demolish-open', this.grid)
            );
        },

        fetchData(showModal = false) {
            if (!showModal && !this.isEnabled) {
                return;
            }

            axios.get(
                this.url.replace('__grid__', this.grid.id)
            ).then(response => {
                this.data = response.data;
                this.initRemaining(this.data.remaining);

                if (showModal) {
                    this.$nextTick(() => this.$modal.modal());
                }
            });
        },

        store() {
            axios.post(
                this.storeUrl.replace('__grid__', this.grid.id)
            );
        },

        destroy() {
            axios.delete(
                this.destroyUrl.replace('__grid__', this.grid.id)
            );
        }
    }
});
