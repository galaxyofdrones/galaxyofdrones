import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url', 'destroyUrl'],

    data() {
        return {
            data: {
                bookmarks: []
            }
        };
    },

    created() {
        EventBus.$on('bookmark-click', this.open);
    },

    computed: {
        isEmpty() {
            return !this.data.bookmarks.length;
        }
    },

    methods: {
        open() {
            this.fetchData();
        },

        fetchData() {
            axios.get(this.url).then(response => {
                this.data = response.data;
                this.$nextTick(() => this.$modal.modal());
            });
        },

        move(bookmark) {
            EventBus.$emit(
                'starmap-move', bookmark.x, bookmark.y
            );

            this.$modal.modal('hide');
        },

        destroy(bookmark) {
            this.data = {
                bookmarks: _.filter(
                    this.data.bookmarks, current => current.id !== bookmark.id
                )
            };

            axios.delete(
                this.destroyUrl.replace('__bookmark__', bookmark.id)
            );
        }
    }
});
