import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    data() {
        return {
            username: '',
            data: {}
        };
    },

    created() {
        EventBus.$on('profile-click', this.open);
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
