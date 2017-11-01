import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    data() {
        return {
            username: ''
        };
    },

    created() {
        EventBus.$on('profile-click', this.open);
    },

    methods: {
        open(username) {
            this.username = username;
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
