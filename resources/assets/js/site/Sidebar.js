import { EventBus } from '../common/event-bus';

export default {
    props: ['planetUrl', 'userUrl', 'nameUrl'],

    data() {
        return {
            isActive: false,
            isEditActive: false,
            isSubscribed: false,
            selected: undefined,
            name: '',
            usedCapacity: 0,
            data: {
                id: undefined,
                user_id: undefined,
                capacity: 0,
                supply: 0,
                used_capacity: 0,
                used_supply: 0,
                used_training_supply: 0,
                display_name: '',
                planets: [],
                resources: [],
                units: []
            }
        };
    },

    created() {
        this.fetchData();
    },

    watch: {
        selected() {
            this.changePlanet();
        }
    },

    computed: {
        resourceLabel() {
            return `${Math.round(this.usedCapacity)}/${this.data.capacity}`;
        },

        resourceProgress() {
            return `${Math.min(100, this.data.usedCapacity / this.data.capacity * 100)}%`;
        },

        unitLabel() {
            return `${this.data.used_supply + this.data.used_training_supply}/${this.data.supply}`;
        },

        unitProgress() {
            return `${this.data.used_supply / this.data.supply * 100}%`;
        },

        unitTrainingProgress() {
            return `${this.data.used_training_supply / this.data.supply * 100}%`;
        },
    },

    methods: {
        fetchData() {
            this.unsubscribe();

            axios.get(this.planetUrl).then(response => {
                this.data = response.data;
                this.selected = this.data.id;

                this.initName();
                this.subscribe();

                EventBus.$emit('planet-changed', this.data);
            });
        },

        changePlanet() {
            if (this.selected === this.data.id) {
                return;
            }

            axios.put(this.userUrl.replace('__planet__', this.selected));
        },

        renamePlanet() {
            if (this.name === this.data.display_name) {
                this.isEditActive = false;
            } else if (!this.name && this.data.name === this.data.display_name) {
                this.initName();
            } else {
                axios.put(this.nameUrl, {
                    name: this.name
                });
            }
        },

        initName() {
            this.isEditActive = false;
            this.name = this.data.display_name;
        },

        initUsedCapacity() {
            this.usedCapacity = this.data.used_capacity;
        },

        subscribe() {
            if (this.isSubscribed) {
                return;
            }

            Echo.private(`user.${this.data.user_id}`).listen('.updated', this.fetchData);
            Echo.private(`planet.${this.data.id}`).listen('.updated', this.fetchData);

            this.isSubscribed = true;
        },

        unsubscribe() {
            if (!this.isSubscribed) {
                return;
            }

            Echo.leave(`user.${this.data.user_id}`);
            Echo.leave(`planet.${this.data.id}`);

            this.isSubscribed = false;
        },

        toggle() {
            this.isActive = !this.isActive;
        },

        toggleEdit() {
            this.isEditActive = !this.isEditActive;

            if (this.isEditActive) {
                this.$nextTick(() => this.$refs.name.focus());
            }
        },

        resourceClass(resource) {
            return `resource-${resource.id}`;
        },

        unitClass(unit) {
            return `unit-${unit.id}`;
        }
    }
};
