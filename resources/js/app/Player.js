import { EventBus } from './event-bus';

export default {
    props: ['url'],

    data() {
        return {
            isSubscribed: false,
            energy: 0,
            energyInterval: undefined,
            data: {
                id: undefined,
                username: '',
                production_rate: 0,
                experience: 0,
                level: 1,
                level_experience: 0,
                next_level_experience: 0,
                notification_count: 0
            }
        };
    },

    created() {
        this.fetchData();
    },

    computed: {
        hasUnread() {
            return this.data.notification_count > 0;
        },

        experienceLabel() {
            return `${this.data.experience} / ${this.data.next_level_experience}`;
        },

        experienceProgress() {
            return `${(this.data.experience - this.data.level_experience) / (this.data.next_level_experience - this.data.level_experience) * 100}%`;
        },

        energyValue() {
            return Math.round(this.energy);
        }
    },

    watch: {
        energy() {
            EventBus.$emit('energy-updated', this.energy);
        }
    },

    methods: {
        fetchData() {
            EventBus.$emit('user-update');

            this.unsubscribe();

            axios.get(this.url).then(response => {
                this.data = response.data;
                this.initEnergy();
                this.subscribe();

                EventBus.$emit('user-updated', this.data);
            });
        },

        initEnergy() {
            this.clearEnergy();
            this.energy = this.data.energy;

            if (this.data.production_rate) {
                this.energyInterval = setInterval(() => this.energy += this.data.production_rate / 3600, 1000);
            }
        },

        clearEnergy() {
            if (!this.energyInterval) {
                return;
            }

            this.energyInterval = clearInterval(this.energyInterval);
        },

        subscribe() {
            if (this.isSubscribed) {
                return;
            }

            Echo.private(`user.${this.data.id}`)
                .listen('.updated', this.fetchData)
                .notification(this.fetchData);

            this.isSubscribed = true;
        },

        unsubscribe() {
            if (!this.isSubscribed) {
                return;
            }

            Echo.leave(`user.${this.data.id}`);

            this.isSubscribed = false;
        },

        openUser() {
            EventBus.$emit('profile-click', this.data.username);
        },

        openMailbox() {
            EventBus.$emit('mailbox-click');
        },

        openSetting() {
            EventBus.$emit('setting-click');
        }
    }
};
