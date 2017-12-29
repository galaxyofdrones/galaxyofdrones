import { EventBus } from '../common/event-bus';
import Modal from './Modal';
import Producer from './Producer';
import Scout from './Scout';
import Trader from './Trader';
import Trainer from './Trainer';

export default Modal.extend({
    props: ['url', 'storeUrl', 'destroyUrl'],

    components: {
        Producer, Scout, Trader, Trainer
    },

    data() {
        return {
            energy: 0,
            grid: {
                id: undefined,
                building_id: undefined
            },
            data: {
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
    },

    computed: {
        building() {
            return this.data.building;
        }
    },

    methods: {
        open(grid) {
            this.grid = grid;
            this.fetchData(true);
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

        canConstruct() {
            return this.energy >= this.data.building.construction_cost;
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
