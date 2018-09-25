import { EventBus } from './event-bus';
import Form from './Form';

export default Form.extend({
    created() {
        EventBus.$on('message-click', this.open);
    },

    methods: {
        open(recipient) {
            this.form.recipient = recipient;

            this.$nextTick(() => this.$modal.modal());
        },

        values() {
            return {
                recipient: '',
                message: ''
            };
        },
    }
});
