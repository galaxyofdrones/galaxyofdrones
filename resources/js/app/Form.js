import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    data() {
        return {
            isSubmitted: false,
            errors: {},
            form: {}
        };
    },

    created() {
        this.form = this.values();
    },

    computed: {
        method() {
            return 'post';
        },

        parameters() {
            return JSON.parse(JSON.stringify(this.form));
        }
    },

    methods: {
        hasError(name) {
            return _.has(this.errors, name);
        },

        values() {
            return {};
        },

        error(name) {
            return _.first(this.errors[name]);
        },

        submit() {
            if (this.isSubmitted) {
                return;
            }

            this.isSubmitted = true;

            axios[this.method](this.url, this.parameters)
                .then(this.handleSuccess)
                .catch(this.handleError);
        },

        handleSuccess() {
            this.isSubmitted = false;
            this.errors = {};
            this.close();
        },

        handleError(error) {
            this.isSubmitted = false;
            this.errors = error.response.data.errors;
        }
    }
});
