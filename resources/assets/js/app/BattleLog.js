import { EventBus } from './event-bus';

export default {
    props: ['isEnabled', 'openAfterHidden', 'url'],

    data() {
        return {
            page: 1,
            collapsed: [],
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

            axios.get(this.url, {
                params: {
                    page: this.page
                }
            }).then(
                response => this.data = response.data
            );
        },

        openUser(username) {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', username)
            );
        },

        isCollapsed(battleLog) {
            return _.includes(
                this.collapsed, battleLog.id
            );
        },

        collapse(battleLog) {
            const index = _.indexOf(
                this.collapsed, battleLog.id
            );

            if (index > -1) {
                this.collapsed.splice(index, 1);
            } else {
                this.collapsed.push(battleLog.id);
            }
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
};
