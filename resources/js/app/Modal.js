import { EventBus } from './event-bus';
import Remaining from './Remaining';

export default Remaining.extend({
    data() {
        return {
            $modal: undefined,
            isEnabled: false
        };
    },

    mounted() {
        this.$modal = $(this.$el)
            .on('show.bs.modal', () => {
                EventBus.$emit('modal-show');
                this.isEnabled = true;
            })
            .on('hidden.bs.modal', () => {
                EventBus.$emit('modal-hidden');
                this.isEnabled = false;
            });
    },

    methods: {
        openAfterHidden(callback) {
            const handler = () => {
                callback();
                EventBus.$off('modal-hidden', handler);
            };

            EventBus.$on('modal-hidden', handler);

            this.close();
        },

        close() {
            this.$modal.modal('hide');
        }
    }
});
