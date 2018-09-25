import { EventBus } from './event-bus';
import Building from './Building';
import HasTab from './HasTab';
import Patrol from './Patrol';
import Trade from './Trade';

export default Building.extend({
    props: [
        'grid',
        'close',
        'url'
    ],

    components: {
        Patrol,
        Trade
    },

    mixins: [
        HasTab
    ],

    data() {
        return {
            selectedTab: 'trade',
            mined: 0,
            planet: {
                id: undefined,
                resource_id: undefined,
                resources: [],
                units: []
            },
            data: {
                travel_time: 0
            }
        };
    },

    created() {
        EventBus.$on('planet-updated', planet => this.planet = planet);
        EventBus.$on('resource-updated', resource => this.mined = resource);
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
});
