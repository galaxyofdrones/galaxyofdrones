import { EventBus } from './event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url'],

    data() {
        return {
            geoJsonPoint: {
                properties: {
                    status: ''
                },
                geometry: {
                    coordinates: []
                }
            },
            planet: {
                id: undefined
            },
            data: {
                username: ''
            }
        };
    },

    created() {
        EventBus.$on('planet-click', this.open);
        EventBus.$on('planet-updated', planet => this.planet = planet);
    },

    computed: {
        isCurrent() {
            return this.properties.id === this.planet.id;
        },

        isFriendly() {
            return this.properties.status === 'friendly';
        },

        properties() {
            return this.geoJsonPoint.properties;
        },

        geometry() {
            return this.geoJsonPoint.geometry;
        }
    },

    methods: {
        open(geoJsonPoint) {
            this.geoJsonPoint = geoJsonPoint;
            this.fetchData();
        },

        fetchData() {
            axios.get(
                this.url.replace('__planet__', this.properties.id)
            ).then(response => {
                this.data = response.data;
                this.$nextTick(() => this.$modal.modal());
            });
        },

        openUser() {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', this.data.username)
            );
        },

        changePlanet() {
            EventBus.$emit('change-planet', this.properties.id);
        },

        openMove(type) {
            this.openAfterHidden(
                () => EventBus.$emit('move-click', type, _.assignIn({}, this.properties, this.data))
            );
        }
    }
});
