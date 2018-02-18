import { EventBus } from './event-bus';
import Vue from 'vue';

export default Vue.extend({
    data() {
        return {
            remaining: 0,
            remainingInterval: undefined,
        };
    },

    created() {
        EventBus.$on('modal-hidden', this.clearRemaining);
    },

    beforeDestroy() {
        EventBus.$off('modal-hidden', this.clearRemaining);
    },

    methods: {
        initRemaining(remaining) {
            this.clearRemaining();
            this.remaining = remaining;

            if (this.remaining < 1) {
                return;
            }

            this.remainingInterval = setInterval(() => {
                this.remaining--;

                if (!this.remaining) {
                    this.clearRemaining();
                }
            }, 1000);
        },

        clearRemaining() {
            if (!this.remainingInterval) {
                return;
            }

            this.remainingInterval = clearInterval(this.remainingInterval);
        }
    }
});
