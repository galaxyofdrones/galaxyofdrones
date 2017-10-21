import { EventBus } from '../common/event-bus';
import Remaining from './Remaining';

export default Remaining.extend({
    props: ['type', 'building'],

    data() {
        return {
            isEnabled: false
        };
    },

    mounted() {
        EventBus.$on('modal-show', () => this.isEnabled = this.type === this.building.type);
        EventBus.$on('modal-hidden', () => this.isEnabled = false);
    }
});
