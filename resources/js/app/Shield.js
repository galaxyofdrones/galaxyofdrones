import { EventBus } from './event-bus';
import Completion from './Completion';

export default {
    props: [
        'isEnabled',
        'url',
        'storeUrl',
        'close',
        'planets'
    ],

    components: {
        Completion
    },

    data() {
        return {
            data: {
                selected: undefined,
                shields: []
            }
        };
    },

    computed: {
        isEmpty() {
            return !this.data.shields.length;
        }
    },

    watch: {
        isEnabled() {
            this.fetchData();
        },

        planets() {
            this.selected = _.get(
                _.first(this.planets), 'id'
            );
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

        store() {
            axios.post(
                this.storeUrl.replace('__planet__', this.selected)
            ).then(this.fetchData);
        },

        move(shield) {
            EventBus.$emit(
                'starmap-move', shield.planet.x, shield.planet.y
            );

            this.close();
        }
    }
};
