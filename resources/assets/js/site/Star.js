import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    data() {
        return {
            geoJsonPoint: {
                properties: {},
                geometry: {
                    coordinates: []
                }
            }
        };
    },

    created() {
        EventBus.$on('star-click', this.open);
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
