import { EventBus } from './event-bus';
import BattleLog from './BattleLog';
import RewardLog from './RewardLog';
import Modal from './Modal';
import HasTab from './HasTab';

export default Modal.extend({
    components: {
        BattleLog, RewardLog
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
