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
        openUser(username) {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', username)
            );
        },

        isCollapsed(battleLog) {
            return _.includes(
                this.collapsed, battleLog.id
            );
        },

        collapse(battleLog) {
            const index = _.indexOf(
                this.collapsed, battleLog.id
            );

            if (index > -1) {
                this.collapsed.splice(index, 1);
            } else {
                this.collapsed.push(battleLog.id);
            }
        }
    }
});
