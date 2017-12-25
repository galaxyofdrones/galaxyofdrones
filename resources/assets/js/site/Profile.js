import { EventBus } from '../common/event-bus';
import Filters from './Filters';
import Modal from './Modal';

export default Modal.extend({
    props: ['url', 'translations'],

    data() {
        return {
            username: '',
            data: {
                created_at: ''
            }
        };
    },

    created() {
        EventBus.$on('profile-click', this.open);
    },

    computed: {
        joined() {
            return this.translations.joined.replace('__datetime__', Filters.fromNow(this.data.created_at));
        }
    },

    methods: {
        open(username) {
            this.username = username;
            this.fetchData();
        },

        fetchData() {
            axios.get(
                this.url.replace('__user__', this.username)
            ).then(response => {
                this.data = response.data;
                this.$nextTick(() => this.$modal.modal());
            });
        }
    }
});
