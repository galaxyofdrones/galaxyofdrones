import { EventBus } from './event-bus';
import Movement from './Movement';

export default {
    props: [
        'isEnabled',
        'url'
    ],

    components: {
        Movement
    },

    data() {
        return {
            data: {
                incoming_movements: []
            }
        };
    },

    created() {
        EventBus.$on('user-update', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return !this.data.incoming_movements.length;
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

            axios.get(this.url).then(
                response => this.data = response.data
            );
        }
    }
};
