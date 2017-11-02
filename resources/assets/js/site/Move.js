import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    data() {
        return {
            type: 0
        };
    },

    created() {
        EventBus.$on('move-click', this.open);
    },

    methods: {
        open(type) {
            this.type = type;
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
