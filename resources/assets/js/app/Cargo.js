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
                solarion: 0,
                resources: [],
                missions: []
            }
        };
    },

    created() {
        EventBus.$on('planet-update', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return !this.data.missions.length;
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

        isCompletable(mission) {
            return !_.some(mission.resources, resource => resource.quantity > _.find(this.data.resources, {
                id: resource.id
            }).quantity);
        },

        store(mission) {
            axios.post(
                this.storeUrl.replace('__mission__', mission.id)
            );
        }
    }
};
