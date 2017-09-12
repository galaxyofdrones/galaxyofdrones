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
            data: {
                id: undefined,
                user_id: undefined,
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
