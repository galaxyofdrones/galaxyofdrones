import { EventBus } from './event-bus';
import BattleLog from './BattleLog';
import MissionLog from './MissionLog';
import Modal from './Modal';

export default Modal.extend({
    components: {
        BattleLog, MissionLog
    },

    data() {
        return {
            selected: 'mission-log'
        };
    },

    created() {
        EventBus.$on('mailbox-click', this.open);
    },

    computed: {
        isMissionLogSelected() {
            return this.selected === 'mission-log';
        },

        isBattleLogSelected() {
            return this.selected === 'battle-log';
        }
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        },

        selectMissionLog() {
            this.selected = 'mission-log';
        },

        selectBattleLog() {
            this.selected = 'battle-log';
        }
    }
});
