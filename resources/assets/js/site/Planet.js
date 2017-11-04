import { EventBus } from '../common/event-bus';
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
            this.fetchData(true);
        },

        fetchData(showModal = false) {
            if (!showModal && !this.isEnabled) {
                return;
            }

            axios.get(
                this.url.replace('__planet__', this.properties.id)
            ).then(response => {
                this.data = response.data;

                if (showModal) {
                    this.$nextTick(() => this.$modal.modal());
                }
            });
        },

        openUser() {
            this.openAfterHidden(
                () => EventBus.$emit('profile-click', this.data.username)
            );
        },

        openMove(type) {
            this.openAfterHidden(
                () => EventBus.$emit('move-click', type, _.assignIn({}, this.properties, this.data))
            );
        }
    }
});
