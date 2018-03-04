import { EventBus } from './event-bus';
import Armory from './Armory';
import Cargo from './Cargo';
import Laboratory from './Laboratory';
import Modal from './Modal';

export default Modal.extend({
    props: ['url', 'storeUrl'],

    components: {
        Armory, Cargo, Laboratory
    },

    data() {
        return {
            selected: undefined,
            selectedTab: 'armory',
            data: {
                capital_id: undefined,
                capital_change_remaining: 0,
                incoming_trade_movement: 0,
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
            return !this.data.incoming_trade_movement && this.selected !== this.data.capital_id;
        }
    },

    methods: {
        isSelectedTab(value) {
            return this.selectedTab === value;
        },

        selectTab(value) {
            this.selectedTab = value;
        },

        open() {
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
