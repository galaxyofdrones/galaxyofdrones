import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

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
        EventBus.$on('trophy-click', this.open);
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

    methods: {
        open() {
            this.fetchData(true);
        },

        fetchData(showModal = false) {
            if (!showModal && !this.isEnabled) {
                return;
            }

            axios.get(this.url, {
                params: {
                    page: this.page
                }
            }).then(response => {
                this.data = response.data;

                if (showModal) {
                    this.$nextTick(() => this.$modal.modal());
                }
            });
        },

        openUser(username) {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', username)
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
