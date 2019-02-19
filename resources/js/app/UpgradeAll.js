import { EventBus } from '../event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    created() {
        EventBus.$on('upgrade-all-open', this.open);
    },

    methods: {
        open() {
            this.$modal.modal();
        },

        upgradeAll() {
            axios.post(this.url).then(this.close);
        }
    }
});
