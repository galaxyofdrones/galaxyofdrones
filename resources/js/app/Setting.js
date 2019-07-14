import { EventBus } from '../event-bus';
import DeveloperSetting from './DeveloperSetting';
import Form from './Form';
import HasTab from './HasTab';

export default Form.extend({
    components: {
        DeveloperSetting
    },

    mixins: [
        HasTab
    ],

    data() {
        return {
            selectedTab: 'profile',
            user: {
                email: '',
                is_notification_enabled: true
            }
        };
    },

    created() {
        EventBus.$on('setting-click', this.open);
        EventBus.$on('user-updated', user => { this.user = user; });
    },

    computed: {
        method() {
            return 'put';
        }
    },

    methods: {
        open() {
            this.form = this.values();

            this.$nextTick(() => this.$modal.modal());
        },

        values() {
            return {
                email: this.user.email,
                password: '',
                password_confirmation: '',
                is_notification_enabled: this.user.is_notification_enabled
            };
        }
    }
});
