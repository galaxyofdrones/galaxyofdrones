import { EventBus } from '../common/event-bus';
import Remaining from './Remaining';

export default Remaining.extend({
    data() {
        return {
            $modal: undefined,
            isEnabled: false
        };
    },

    created() {
        EventBus.$on('modal-show', () => this.isEnabled = true);
        EventBus.$on('modal-hidden', () => this.isEnabled = false);
    },

    mounted() {
        this.$modal = $(this.$el)
            .on('show.bs.modal', () => EventBus.$emit('modal-show'))
            .on('hidden.bs.modal', () => EventBus.$emit('modal-hidden'));
    },

    methods: {
        openAfterHidden(callback) {
            const handler = () => {
                callback();
                EventBus.$off('modal-hidden', handler);
            };

            EventBus.$on('modal-hidden', handler);

            this.$modal.modal('hide');
        }
    }
});
