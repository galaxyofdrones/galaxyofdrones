import { EventBus } from '../event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: [
        'url',
        'storeUrl'
    ],

    data() {
        return {
            energy: 0,
            data: {
                has_solarion: true,
                upgrade_count: 0,
                upgrade_cost: 0
            }
        };
    },

    created() {
        EventBus.$on('upgrade-all-open', this.open);
        EventBus.$on('energy-updated', energy => this.energy = energy);
    },

    computed: {
        canStore() {
            return this.data.has_solarion
                && this.data.upgrade_cost
                && this.energy >= this.data.upgrade_cost;
        }
    },

    methods: {
        open() {
            this.fetchData(true);
        },

        fetchData(showModal = false) {
            if (!showModal && !this.isEnabled) {
                return;
            }

            axios.get(this.url).then(response => {
                this.data = response.data;

                if (showModal) {
                    this.$nextTick(() => this.$modal.modal());
                }
            });
        },

        store() {
            axios.post(this.storeUrl).then(this.close);
        }
    }
});
