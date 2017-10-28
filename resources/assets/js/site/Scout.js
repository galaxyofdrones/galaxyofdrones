import { EventBus } from '../common/event-bus';
import ModalBody from './ModalBody';
import ScoutItem from './ScoutItem';

export default ModalBody.extend({
    props: ['grid', 'url'],

    components: {
        ScoutItem
    },

    data() {
        return {
            data: {
                incoming_movements: [],
                outgoing_movements: []
            }
        };
    },

    created() {
        EventBus.$on('planet-update', this.fetchData);
    },

    computed: {
        isEmpty() {
            return !this.data.incoming_movements.length && !this.data.outgoing_movements.length;
        }
    },

    watch: {
        isEnabled() {
            this.fetchData();
        }
    },

    methods: {
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(
                this.url.replace('__grid__', this.grid.id)
            ).then(
                response => this.data = response.data
            );
        }
    }
});
