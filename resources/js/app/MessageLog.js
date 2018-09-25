import { EventBus } from './event-bus';
import CompletionLog from './CompletionLog';

export default CompletionLog.extend({
    props: ['openAfterHidden'],

    data() {
        return {
            collapsed: []
        };
    },

    methods: {
        isCollapsed(message) {
            return _.includes(
                this.collapsed, message.id
            );
        },

        collapse(message) {
            const index = _.indexOf(
                this.collapsed, message.id
            );

            if (index > -1) {
                this.collapsed.splice(index, 1);
            } else {
                this.collapsed.push(message.id);
            }
        },

        openUser(username) {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', username)
            );
        },

        sendMessage(recipient) {
            this.openAfterHidden(
                () => EventBus.$emit('message-click', recipient)
            );
        }
    }
});
