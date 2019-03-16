import { EventBus } from '../event-bus';
import Completion from './Completion';
import Routing from './Routing';

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

    mixins: [
        Routing
    ],

    data() {
        return {
            selected: undefined,
            data: {
                can_store: true,
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
            if (!_.find(this.planets, { id: this.selected })) {
                this.selected = _.get(
                    _.first(this.planets), 'id'
                );
            }
        }
    },

    methods: {
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(this.url).then(
                response => { this.data = response.data; }
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
