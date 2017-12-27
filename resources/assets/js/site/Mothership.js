import { EventBus } from '../common/event-bus';
import Laboratory from './Laboratory';
import Modal from './Modal';

export default Modal.extend({
    components: {
        Laboratory
    },

    created() {
        EventBus.$on('mothership-click', this.open);
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
