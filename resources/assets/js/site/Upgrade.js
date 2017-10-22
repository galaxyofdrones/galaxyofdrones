import { EventBus } from '../common/event-bus';
import Modal from './Modal';
import Training from './Training';
import Transmute from './Transmute';

export default Modal.extend({
    props: ['url', 'storeUrl', 'destroyUrl'],

    components: {
        Training, Transmute
    },

    data() {
        return {
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
        EventBus.$on('grid-click', this.open);
        EventBus.$on('planet-update', this.fetchData);
    },

    computed: {
        building() {
            return this.data.building;
        }
    },

    methods: {
        open(grid) {
            if (!grid.building_id) {
                return;
            }

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
