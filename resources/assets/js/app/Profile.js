import { EventBus } from './event-bus';
import PlanetList from './PlanetList';
import Filters from './Filters';
import Modal from './Modal';

export default Modal.extend({
    props: [
        'url',
        'blockUrl',
        'canMove',
        'translations'
    ],

    components: {
        PlanetList
    },

    data() {
        return {
            isBlocked: false,
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
                this.isBlocked = this.data.isBlocked;

                this.$nextTick(
                    () => this.$modal.modal()
                );
            });
        },

        sendMessage() {
            this.openAfterHidden(
                () => EventBus.$emit('message-click', this.username)
            );
        },

        toggleBlock() {
            this.isBlocked = !this.isBlocked;

            axios.put(
                this.blockUrl.replace('__user__', this.username)
            );
        }
    }
});
