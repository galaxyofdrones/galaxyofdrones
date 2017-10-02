import { EventBus } from '../common/event-bus';

export default {
    props: ['url', 'storeUrl', 'destroyUrl'],

    data() {
        return {
            $modal: undefined,
            remaining: 0,
            remainingInterval: undefined,
            selected: {
                id: undefined
            },
            grid: {
                id: undefined
            },
            data: {
                buildings: []
            }
        };
    },

    mounted() {
        this.$modal = $(this.$el).on('hide.bs.modal', this.clearRemaining);

        EventBus.$on('grid-click', this.open);
    },

    methods: {
        open(grid) {
            if (grid.building_id) {
                return;
            }

            this.grid = grid;
            this.fetchData();
        },

        fetchData() {
            const url = this.url.replace('__grid__', this.grid.id);

            axios.get(url).then(response => {
                this.data = response.data;
                this.selected = _.first(this.data.buildings);
                this.$modal.modal();
                this.initRemaining();
            });
        },

        store() {
            const url = this.storeUrl.replace('__grid__', this.grid.id).replace('__building__', this.selected.id);

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
                        this.clearRemaining();
                    }
                }, 1000);
            }
        },

        clearRemaining() {
            if (!this.remainingInterval) {
                return;
            }

            clearInterval(this.remainingInterval);
        },

        buildingClass(building) {
            return `building-${building.id}`;
        },

        isSelected(building) {
            return this.selected.id === building.id;
        },

        select(building) {
            this.selected = building;
        }
    }
};
