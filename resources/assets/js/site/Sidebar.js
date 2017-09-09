import { EventBus } from '../common/event-bus';

export default {
    props: ['planetUrl', 'userUrl', 'nameUrl'],

    data() {
        return {
            isActive: false,
            isEditActive: false,
            selected: undefined,
            name: '',
            data: {
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
            axios.get(this.planetUrl).then(response => {
                this.data = response.data;
                this.isEditActive = false;
                this.selected = this.data.id;
                this.name = this.data.display_name;

                EventBus.$emit('planet-changed', this.data);
            });
        },

        changePlanet() {
            if (this.selected !== this.data.id) {
                axios.put(this.userUrl.replace('__planet__', this.selected)).then(() => this.fetchData());
            }
        },

        renamePlanet() {
            axios.put(this.nameUrl, {
                name: this.name
            }).then(() => this.fetchData());
        },

        toggle() {
            this.isActive = !this.isActive;
        },

        toggleEdit() {
            this.isEditActive = !this.isEditActive;

            if (this.isEditActive) {
                this.$nextTick(() => this.$refs.name.focus());
            }
        }
    }
};
