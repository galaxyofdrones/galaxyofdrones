import { EventBus } from '../common/event-bus';
import Laboratory from './Laboratory';
import Modal from './Modal';

export default Modal.extend({
    props: ['url', 'storeUrl'],

    components: {
        Laboratory
    },

    data() {
        return {
            selected: undefined,
            data: {
                capital_id: undefined,
                capital_change_remaining: 0,
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
            return this.selected !== this.data.capital_id;
        }
    },

    methods: {
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
