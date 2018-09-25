import { EventBus } from './event-bus';

export default {
    props: [
        'url',
    ],

    data() {
        return {
            data: {
                incoming: 0
            }
        };
    },

    created() {
        EventBus.$on('user-updated', () => this.fetchData());
    },

    computed: {
        isEnabled() {
            return this.data.incoming > 0;
        }
    },

    methods: {
        fetchData() {
            axios.get(this.url).then(
                response => this.data = response.data
            );
        },

        openRadar() {
            EventBus.$emit('mothership-click', 'radar');
        }
    }
};
