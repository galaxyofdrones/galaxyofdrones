import { EventBus } from './event-bus';
import Vue from 'vue';

export default Vue.extend({
    props: [
        'isEnabled',
        'url'
    ],

    data() {
        return {
            page: 1,
            data: {
                current_page: 1,
                last_page: 1,
                total: 0
            }
        };
    },

    created() {
        EventBus.$on('user-updated', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return this.data.total === 0;
        },

        hasPrev() {
            return this.data.current_page > 1;
        },

        hasNext() {
            return this.data.current_page < this.data.last_page;
        },

        dataUrl() {
            return this.url;
        }
    },

    watch: {
        isEnabled() {
            this.page = 1;
            this.fetchData();
        }
    },

    methods: {
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(this.dataUrl, {
                params: {
                    page: this.page
                }
            }).then(
                response => this.data = response.data
            );
        },

        prevPage() {
            this.changePage(this.page - 1);
        },

        nextPage() {
            this.changePage(this.page + 1);
        },

        changePage(page) {
            if (this.page === page) {
                return;
            }

            if (page < 1 || page > this.data.last_page) {
                return;
            }

            this.page = page;
            this.fetchData();
        }
    }
});
