import { EventBus } from '../common/event-bus';
import Laboratory from './Laboratory';
import MissionControl from './MissionControl';
import Modal from './Modal';

export default Modal.extend({
    props: ['url', 'storeUrl'],

    components: {
        Laboratory, MissionControl
    },

    data() {
        return {
            selected: undefined,
            selectedTab: 'mission-control',
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
        isMissionControlSelected() {
            return this.selectedTab === 'mission-control';
        },

        isLaboratorySelected() {
            return this.selectedTab === 'laboratory';
        },

        canHyperjump() {
            return !this.data.incoming_trade_movement && this.selected !== this.data.capital_id;
        }
    },

    methods: {
        open() {
            this.fetchData(true);
        },

        selectMissionControl() {
            this.selectedTab = 'mission-control';
        },

        selectLaboratory() {
            this.selectedTab = 'laboratory';
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
