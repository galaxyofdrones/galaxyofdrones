export default {
    props: ['size', 'maxZoom', 'geoJsonUrl', 'tileUrl', 'imagePath'],

    data() {
        return {
            map: undefined,
            zoom: 0
        };
    },

    created() {
        this.zoom = Math.ceil(Math.log(this.size / 256) / Math.log(2));
    },

    mounted() {
        this.initLeaflet();
    },

    methods: {
        initLeaflet() {
            L.Icon.Default.imagePath = `${this.imagePath}/`;

            this.map = L.map(this.$el, {
                attributionControl: false,
                boxZoom: false,
                crs: L.CRS.Simple,
                minZoom: 0,
                maxZoom: this.maxZoom,
                zoomControl: false
            });

            this.map.setView(this.center(), this.maxZoom);

            L.tileLayer(this.tileUrl, {
                noWrap: true,
                bounds: L.latLngBounds(this.southWest(), this.northEast()),
            }).addTo(this.map);

            const geoJsonLayer = L.geoJson.ajax(this.geoJson(), {
                pointToLayer: (geoJsonPoint, latlng) => {
                    const point = this.map.unproject([
                        latlng.lng, latlng.lat
                    ], this.maxZoom);

                    const size = (geoJsonPoint.properties.size + 16) / this.multiplier();

                    const options = {
                        className: geoJsonPoint.properties.type === 'planet'
                            ? `leaflet-div-icon ${geoJsonPoint.properties.status}`
                            : 'leaflet-div-icon',
                        iconSize: [
                            size, size
                        ]
                    };

                    if (this.map.getZoom() >= 8) {
                        options.html = `<span>${geoJsonPoint.properties.name}</span>`;
                    }

                    const marker = L.marker(point, {
                        title: geoJsonPoint.properties.name,
                        icon: L.divIcon(options)
                    });

                    marker.on('click', () => {});

                    return marker;
                }
            });

            geoJsonLayer.ajaxParams.headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            };

            geoJsonLayer.addTo(this.map);

            this.map.on('zoomstart', () => geoJsonLayer.clearLayers());
            this.map.on('moveend', () => geoJsonLayer.refresh(this.geoJson()));
        },

        geoJson() {
            const bounds = this.map.getPixelBounds();
            const multiplier = this.multiplier();

            return this.geoJsonUrl
                .replace('__zoom__', this.map.getZoom())
                .replace('__bounds__', [
                    bounds.min.x * multiplier,
                    bounds.min.y * multiplier,
                    bounds.max.x * multiplier,
                    bounds.max.y * multiplier
                ].join(','));
        },

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
        },

        multiplier() {
            return Math.pow(2, this.maxZoom - this.map.getZoom());
        }
    }
};
