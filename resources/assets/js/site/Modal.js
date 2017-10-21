import { EventBus } from '../common/event-bus';
import Remaining from './Remaining';

export default Remaining.extend({
    data() {
        return {
            $modal: undefined,
            isEnabled: false
        };
    },

    mounted() {
        this.$modal = $(this.$el).on('show.bs.modal', () => {
            EventBus.$emit('modal-show');
            this.isEnabled = true;
        }).on('hidden.bs.modal', () => {
            EventBus.$emit('modal-hidden');
            this.isEnabled = false;
        });
    }
});
