import { EventBus } from './event-bus';
import HasTab from './HasTab';
import Leaderboard from './Leaderboard';
import Modal from './Modal';

export default Modal.extend({
    components: {
        Leaderboard
    },

    mixins: [
        HasTab
    ],

    data() {
        return {
            selectedTab: 'pve'
        };
    },

    created() {
        EventBus.$on('trophy-click', this.open);
    },

    methods: {
        open() {
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
