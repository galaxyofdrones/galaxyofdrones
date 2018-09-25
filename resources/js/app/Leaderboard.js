import { EventBus } from './event-bus';
import CompletionLog from './CompletionLog';

export default CompletionLog.extend({
    props: ['openAfterHidden'],

    methods: {
        openUser(username) {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', username)
            );
        },

        rank(index) {
            return this.data.from + index;
        }
    }
});
