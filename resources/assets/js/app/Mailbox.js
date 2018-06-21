import { EventBus } from './event-bus';
import BattleLog from './BattleLog';
import CompletionLog from './CompletionLog';
import MessageLog from './MessageLog';
import Modal from './Modal';
import HasTab from './HasTab';

export default Modal.extend({
    components: {
        BattleLog,
        CompletionLog,
        MessageLog
    },

    mixins: [
        HasTab
    ],

    data() {
        return {
            selectedTab: 'mission-log'
        };
    },

    created() {
        EventBus.$on('mailbox-click', this.open);
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
