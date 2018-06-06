import { EventBus } from './event-bus';
import Armory from './Armory';
import Cargo from './Cargo';
import HasTab from './HasTab';
import Laboratory from './Laboratory';
import Radar from './Radar';
import Shield from './Shield';
import Modal from './Modal';

export default Modal.extend({
    props: [
        'url',
        'storeUrl'
    ],

    components: {
        Armory,
        Cargo,
        Laboratory,
        Radar,
        Shield
    },

    mixins: [
        HasTab
    ],

    data() {
        return {
            selected: undefined,
            selectedTab: 'cargo',
            data: {
                capital_id: undefined,
                capital_change_remaining: 0,
                incoming_capital_movement_count: 0,
                planets: []
            }
        };
    },

    created() {
        EventBus.$on('mothership-click', this.open);
        EventBus.$on('user-updated', () => this.fetchData());
    },

    computed: {
        canHyperjump() {
            return !this.data.incoming_capital_movement_count && this.selected !== this.data.capital_id;
        }
    },

    methods: {
        open(tab) {
            if (tab) {
                this.selectTab(tab);
            }

            this.fetchData(true);
        },

        fetchData(showModal = false) {
            if (!showModal && !this.isEnabled) {
                return;
            }

            axios.get(this.url).then(response => {
                this.data = response.data;
                this.selected = this.data.capital_id;
                this.initRemaining(this.data.capital_change_remaining);

                if (showModal) {
                    this.$nextTick(() => this.$modal.modal());
                }
            });
        },

        store() {
            axios.put(
                this.storeUrl.replace('__planet__', this.selected)
            );
        }
    }
});
