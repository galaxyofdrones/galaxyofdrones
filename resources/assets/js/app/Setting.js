import { EventBus } from './event-bus';
import Form from './Form';

export default Form.extend({
    created() {
        EventBus.$on('setting-click', this.open);
        EventBus.$on('user-updated', user => this.form.email = user.email);
    },

    computed: {
        method() {
            return 'put';
        }
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        },

        values() {
            return {
                email: '',
                password: '',
                password_confirmation: ''
            };
        }
    }
});
