import { EventBus } from './event-bus';
import Completion from './Completion';

export default {
    props: [
        'isEnabled',
        'url',
        'storeUrl'
    ],

    components: {
        Completion
    },

    data() {
        return {
            data: {
                units: [],
                expeditions: []
            }
        };
    },

    created() {
        EventBus.$on('planet-update', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return !this.data.expeditions.length;
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

        isCompletable(expedition) {
            return !_.some(expedition.units, unit => unit.quantity > _.find(this.data.units, {
                id: unit.id
            }).quantity);
        },

        store(expedition) {
            axios.post(
                this.storeUrl.replace('__expedition__', expedition.id)
            );
        }
    }
};
