import { EventBus } from './event-bus';

export default {
    props: ['isEnabled', 'url'],

    data() {
        return {
            data: {
                units: []
            }
        };
    },

    created() {
        EventBus.$on('planet-update', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return true;
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
