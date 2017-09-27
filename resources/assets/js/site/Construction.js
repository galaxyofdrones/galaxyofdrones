import { EventBus } from '../common/event-bus';

export default {
    props: ['url'],

    data() {
        return {
            $modal: undefined,
            grid: {
                id: undefined
            },
            data: {
                in_progress: false,
                buildings: []
            }
        };
    },

    mounted() {
        this.$modal = $(this.$el);

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
                this.$modal.modal();
            });
        }
    }
};
