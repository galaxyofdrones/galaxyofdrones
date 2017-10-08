import { EventBus } from '../common/event-bus';

export default {
    props: ['url', 'storeUrl', 'destroyUrl'],

    data() {
        return {
            $modal: undefined,
            remaining: 0,
            remainingInterval: undefined,
            grid: {
                id: undefined,
                building_id: undefined
            },
            data: {
                building: {},
                upgrade: {}
            }
        };
    },

    mounted() {
        this.$modal = $(this.$el).on('hide.bs.modal', this.clearRemaining);

        EventBus.$on('grid-click', this.open);
    },

    computed: {
        building() {
            return this.data.building;
        }
    },

    methods: {
        open(grid) {
            if (!grid.building_id) {
                return;
            }

            this.grid = grid;
            this.fetchData();
        },

        fetchData() {
            const url = this.url.replace('__grid__', this.grid.id);

            axios.get(url).then(response => {
                this.data = response.data;
                this.$modal.modal();
                this.initRemaining();
            });
        },

        store() {
            const url = this.storeUrl.replace('__grid__', this.grid.id);

            axios.post(url).then(() => this.$modal.modal('hide'));
        },

        destroy() {
            const url = this.destroyUrl.replace('__grid__', this.grid.id);

            axios.delete(url).then(() => this.$modal.modal('hide'));
        },

        initRemaining() {
            this.clearRemaining();
            this.remaining = this.data.remaining;

            if (this.remaining) {
                this.remainingInterval = setInterval(() => {
                    this.remaining--;

                    if (!this.remaining) {
                        this.$modal.modal('hide');
                    }
                }, 1000);
            }
        },

        clearRemaining() {
            if (!this.remainingInterval) {
                return;
            }

            clearInterval(this.remainingInterval);
        }
    }
};
