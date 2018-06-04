import Modal from './Modal';

export default Modal.extend({
    props: ['id'],

    mounted() {
        if (this.hasStorage && !localStorage.getItem(this.storageKey)) {
            this.open();

            localStorage.setItem(
                this.storageKey, JSON.stringify(true)
            );
        }
    },

    computed: {
        hasStorage() {
            return typeof (Storage) !== 'undefined';
        },

        storageKey() {
            return `whats-new-${this.id}`;
        }
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
