import { EventBus } from './event-bus';
import Movement from './Movement';

export default {
    props: [
        'isEnabled',
        'url',
        'canMove',
        'close',
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
        },

        move(movement) {
            EventBus.$emit(
                'starmap-move', movement.end.x, movement.end.y
            );

            this.close();
        }
    }
};
