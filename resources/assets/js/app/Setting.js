import { EventBus } from './event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    data() {
        return {
            errors: {},
            form: {
                email: '',
                password: '',
                password_confirmation: ''
            }
        };
    },

    created() {
        EventBus.$on('setting-click', this.open);
        EventBus.$on('user-updated', user => this.form.email = user.email);
    },

    computed: {
        parameters() {
            return JSON.parse(JSON.stringify(this.form));
        }
    },

    methods: {
        hasError(name) {
            return this.errors.hasOwnProperty(name);
        },

        error(name) {
            return _.first(this.errors[name]);
        },

        open() {
            this.$nextTick(() => this.$modal.modal());
        },

        submit() {
            axios.put(this.url, this.parameters)
                .then(this.close)
                .catch(error => this.errors = error.response.data.errors);
        }
    }
});
