import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    data() {
        return {
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
            axios[this.method](this.url, this.parameters)
                .then(() => {
                    this.errors = {};
                    this.close();
                })
                .catch(
                    error => { this.errors = error.response.data.errors; }
                );
        }
    }
});
