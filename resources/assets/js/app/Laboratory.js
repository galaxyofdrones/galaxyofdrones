import Research from './Research';
import { EventBus } from './event-bus';

export default {
    props: [
        'isEnabled',
        'url',
        'storeResourceUrl',
        'storeUnitUrl',
        'destroyResourceUrl',
        'destroyUnitUrl'
    ],

    components: {
        Research
    },

    data() {
        return {
            energy: 0,
            data: {
                resource: undefined,
                units: []
            }
        };
    },

    created() {
        EventBus.$on('energy-updated', energy => this.energy = energy);
        EventBus.$on('user-updated', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return !this.data.resource && !this.data.units.length;
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

        isResearchable(research) {
            return this.energy >= research.research_cost;
        },

        storeResource() {
            axios.post(this.storeResourceUrl);
        },

        destroyResource() {
            axios.delete(this.destroyResourceUrl);
        },

        storeUnit(unit) {
            axios.post(
                this.storeUnitUrl.replace('__unit__', unit.id)
            );
        },

        destroyUnit(unit) {
            axios.delete(
                this.destroyUnitUrl.replace('__unit__', unit.id)
            );
        }
    }
};
