import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['free'],

    data() {
        return {
            geoJsonPoint: {
                properties: {
                    resource_id: undefined
                },
                geometry: {
                    coordinates: []
                }
            },
            planet: {}
        };
    },

    created() {
        EventBus.$on('planet-click', this.open);
        EventBus.$on('planet-updated', planet => this.planet = planet);
    },

    computed: {
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
            this.$nextTick(() => this.$modal.modal());
        }
    }
});
