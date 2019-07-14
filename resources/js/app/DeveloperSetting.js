import Form from './Form';

export default Form.extend({
    props: ['settings'],

    computed: {
        method() {
            return 'put';
        }
    },

    methods: {
        values() {
            return {
                title: this.settings.title,
                description: this.settings.description,
                author: this.settings.author
            };
        },

        handleSuccess() {
            window.location.reload();
        }
    }
});
