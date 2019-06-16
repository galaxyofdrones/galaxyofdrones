import { EventBus } from '../event-bus';
import Modal from './Modal';

export default Modal.extend({
    created() {
        EventBus.$on('donation-click', this.open);
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
