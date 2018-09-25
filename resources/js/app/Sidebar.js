import { EventBus } from './event-bus';
import PerfectScrollbar from 'perfect-scrollbar';

export default {
    props: [
        'planetUrl',
        'planetNameUrl',
        'userCurrentUrl'
    ],

    data() {
        return {
            perfectScrollbar: undefined,
            isActive: false,
            isEditActive: false,
            isSubscribed: false,
            selected: undefined,
            name: '',
            usedCapacity: 0,
            resource: 0,
            resourceInterval: undefined,
            data: {
                id: undefined,
                resource_id: undefined,
                user_id: undefined,
                capacity: 0,
                supply: 0,
                solarion: 0,
                mining_rate: 0,
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

        EventBus.$on('change-planet', planet => this.selected = planet);
    },

    mounted() {
        this.initPerfectScrollbar();
    },

    watch: {
        selected() {
            this.changePlanet();
        },

        resource() {
            EventBus.$emit('resource-updated', this.resource);
        }
    },

    computed: {
        isResourceFull() {
            return this.data.capacity === this.usedCapacity;
        },

        isUnitFull() {
            return this.data.supply === this.data.used_supply;
        },

        resourceLabel() {
            return `${Math.round(this.usedCapacity)} / ${this.data.capacity}`;
        },

        resourceProgress() {
            return `${Math.min(100, this.usedCapacity / this.data.capacity * 100)}%`;
        },

        unitLabel() {
            return `${this.data.used_supply + this.data.used_training_supply} / ${this.data.supply}`;
        },

        unitProgress() {
            return `${this.data.used_supply / this.data.supply * 100}%`;
        },

        unitTrainingProgress() {
            return `${this.data.used_training_supply / this.data.supply * 100}%`;
        }
    },

    methods: {
        fetchData() {
            EventBus.$emit('planet-update');

            this.unsubscribe();

            axios.get(this.planetUrl).then(response => {
                this.data = response.data;
                this.selected = this.data.id;

                this.initName();
                this.initResource();
                this.subscribe();

                EventBus.$emit('planet-updated', this.data);
            });
        },

        renamePlanet() {
            if (this.name === this.data.display_name) {
                this.isEditActive = false;
            } else if (!this.name && this.data.name === this.data.display_name) {
                this.initName();
            } else {
                axios.put(this.planetNameUrl, {
                    name: this.name
                });
            }
        },

        changePlanet() {
            if (this.selected === this.data.id) {
                return;
            }

            axios.put(
                this.userCurrentUrl.replace('__planet__', this.selected)
            );
        },

        initName() {
            this.isEditActive = false;
            this.name = this.data.display_name;
        },

        initResource() {
            this.clearResource();
            this.usedCapacity = this.data.used_capacity;

            this.resource = _.get(_.find(this.data.resources, {
                id: this.data.resource_id
            }), 'quantity', 0);

            if (this.data.mining_rate && this.data.capacity !== this.usedCapacity) {
                this.resourceInterval = setInterval(() => {
                    const quantity = Math.min(
                        this.data.capacity - this.usedCapacity, this.data.mining_rate / 3600
                    );

                    this.resource += quantity;
                    this.usedCapacity += quantity;

                    if (this.data.capacity === this.usedCapacity) {
                        this.clearResource();
                    }
                }, 1000);
            }
        },

        clearResource() {
            if (!this.resourceInterval) {
                return;
            }

            this.resourceInterval = clearInterval(this.resourceInterval);
        },

        initPerfectScrollbar() {
            this.perfectScrollbar = new PerfectScrollbar(this.$refs.scrollbar);
        },

        updatePerfectScrollbar() {
            this.perfectScrollbar.update();
        },

        subscribe() {
            if (this.isSubscribed) {
                return;
            }

            Echo.private(`planet.${this.data.id}`).listen('.updated', this.fetchData);
            EventBus.$on('user-update', this.fetchData);

            this.isSubscribed = true;
        },

        unsubscribe() {
            if (!this.isSubscribed) {
                return;
            }

            Echo.leave(`planet.${this.data.id}`);
            EventBus.$off('user-update', this.fetchData);

            this.isSubscribed = false;
        },

        toggle() {
            this.isActive = !this.isActive;

            if (this.isActive) {
                this.updatePerfectScrollbar();
            }
        },

        toggleEdit() {
            this.isEditActive = !this.isEditActive;

            if (this.isEditActive) {
                this.$nextTick(() => this.$refs.name.focus());
            }
        },

        resourceQuantity(resource) {
            if (resource.id === this.data.resource_id) {
                return this.resource;
            }

            return resource.quantity;
        },

        resourceValue(resource) {
            return Math.round(
                this.resourceQuantity(resource)
            );
        },

        openMothership() {
            EventBus.$emit('mothership-click');
        },

        openTrophy() {
            EventBus.$emit('trophy-click');
        }
    }
};
