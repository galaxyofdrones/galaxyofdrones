import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    data() {
        return {
            grid: {}
        };
    },

    created() {
        EventBus.$on('demolish-open', this.open);
    },

    methods: {
        open(grid) {
            this.grid = grid;
            this.$modal.modal();
        },

        demolish() {
            axios.delete(
                this.url.replace('__grid__', this.grid.id)
            ).then(() => this.$modal.modal('hide'));
        },

        cancel() {
            this.$modal.modal('hide');
        }
    }
});
