import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['url', 'scout', 'attack', 'occupy', 'support', 'transport'],

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
            this.$modal.modal('hide');
            EventBus.$emit('profile-click', this.data.username);
        },

        openScoutMove() {
            this.$modal.modal('hide');
            EventBus.$emit('move-click', this.scout);
        },

        openAttackMove() {
            this.$modal.modal('hide');
            EventBus.$emit('move-click', this.attack);
        },

        openOccupyMove() {
            this.$modal.modal('hide');
            EventBus.$emit('move-click', this.occupy);
        },

        openSupportMove() {
            this.$modal.modal('hide');
            EventBus.$emit('move-click', this.support);
        },

        openTransportMove() {
            this.$modal.modal('hide');
            EventBus.$emit('move-click', this.transport);
        }
    }
});
