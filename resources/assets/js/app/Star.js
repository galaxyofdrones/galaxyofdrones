import { EventBus } from './event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: [
        'url',
        'bookmarkStoreUrl'
    ],

    data() {
        return {
            geoJsonPoint: {
                properties: {},
                geometry: {
                    coordinates: []
                }
            },
            data: {
                isBookmarked: false,
                hasExpedition: false
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
            this.fetchData();
        },

        fetchData() {
            axios.get(
                this.url.replace('__star__', this.properties.id)
            ).then(response => {
                this.data = response.data;
                this.$nextTick(() => this.$modal.modal());
            });
        },

        bookmark() {
            this.data.isBookmarked = true;

            axios.post(
                this.bookmarkStoreUrl.replace('__star__', this.properties.id)
            );
        },

        showExpedition() {
            this.openAfterHidden(
                () => EventBus.$emit('mothership-click', 'armory')
            );
        }
    }
});
