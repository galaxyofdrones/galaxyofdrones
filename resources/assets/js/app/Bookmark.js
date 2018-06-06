import { EventBus } from './event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: [
        'url',
        'destroyUrl'
    ],

    data() {
        return {
            page: 1,
            data: {
                current_page: 1,
                from: 0,
                last_page: 1,
                to: 0,
                total: 0
            }
        };
    },

    created() {
        EventBus.$on('bookmark-click', this.open);
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
            this.page = 1;
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

        move(bookmark) {
            EventBus.$emit(
                'starmap-move', bookmark.x, bookmark.y
            );

            this.close();
        },

        destroy(bookmark) {
            axios.delete(
                this.destroyUrl.replace('__bookmark__', bookmark.id)
            ).then(() => {
                if (this.page > 1 && (this.data.to - this.data.from) === 0) {
                    this.prevPage();
                } else {
                    this.fetchData();
                }
            });
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
