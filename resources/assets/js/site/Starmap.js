export default {
    props: ['size', 'maxZoom', 'geoJsonUrl', 'tileUrl', 'imagePath'],

    data() {
        return {
            map: undefined,
            zoom: 0,
        };
    },

    created() {
        this.zoom = Math.ceil(Math.log(this.size / 256) / Math.log(2));
    },

    mounted() {
        this.initLeaflet();
    },

    computed: {
        center() {
            return this.map.unproject([
                this.size / 2, this.size / 2
            ], this.zoom);
        },

        southWest() {
            return this.map.unproject([
                0, 0
            ], this.zoom);
        },

        northEast() {
            return this.map.unproject([
                this.size, this.size
            ], this.zoom);
        }
    },

    methods: {
        initLeaflet() {
            L.Icon.Default.imagePath = this.imagePath;

            this.map = L.map(this.$el, {
                attributionControl: false,
                boxZoom: false,
                crs: L.CRS.Simple,
                minZoom: 0,
                maxZoom: this.maxZoom,
                zoomControl: false
            });

            this.map.setView(this.center, this.maxZoom);

            L.tileLayer(this.tileUrl, {
                noWrap: true,
                bounds: L.latLngBounds(this.southWest, this.northEast),
            }).addTo(this.map);
        }
    }
};
