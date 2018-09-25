import { EventBus } from './event-bus';
import Form from './Form';

export default Form.extend({
    data() {
        return {
            email: ''
        };
    },

    created() {
        EventBus.$on('setting-click', this.open);
        EventBus.$on('user-updated', user => this.email = user.email);
    },

    computed: {
        method() {
            return 'put';
        }
    },

    methods: {
        open() {
            this.form.email = this.email;

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
