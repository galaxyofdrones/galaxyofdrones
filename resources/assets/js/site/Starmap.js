import { EventBus } from '../common/event-bus';

export default {
    props: [
        'size', 'maxZoom', 'geoJsonUrl', 'tileUrl', 'imagePath', 'zoomInTitle', 'zoomOutTitle', 'bookmarkTitle'
    ],

    data() {
        return {
            geoJsonLayer: undefined,
            map: undefined,
            zoom: 0,
            planet: {
                id: undefined,
                x: 0,
                y: 0
            }
        };
    },

    created() {
        this.zoom = Math.ceil(Math.log(this.size / 256) / Math.log(2));
    },

    mounted() {
        EventBus.$on('planet-updated', planet => {
            const isSamePlanet = planet.id === this.planet.id;

            this.planet = planet;

            if (!this.map) {
                this.initLeaflet();
            } else if (isSamePlanet) {
                this.geoJsonLayer.refresh();
            } else {
                this.map.setView(this.center(), this.maxZoom);
            }
        });

        EventBus.$on('starmap-move', (x, y) => this.map.setView(
            this.unproject(x, y), this.maxZoom
        ));
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

            this.geoJsonLayer = L.geoJson.ajax(this.geoJson(), {
                coordsToLatLng: coords => this.map.unproject([
                    coords[0], coords[1]
                ], this.maxZoom),

                pointToLayer: (geoJsonPoint, latlng) => {
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

                    const marker = L.marker(latlng, {
                        title: geoJsonPoint.properties.name,
                        icon: L.divIcon(options)
                    });

                    marker.on('click', () => EventBus.$emit(`${geoJsonPoint.properties.type}-click`, geoJsonPoint));

                    return marker;
                },

                style: feature => {
                    if (feature.geometry.type === 'LineString') {
                        return {
                            className: feature.properties.type < 3
                                ? `leaflet-movement ${feature.properties.status}-attack`
                                : `leaflet-movement ${feature.properties.status}`
                        };
                    }
                }
            });

            this.geoJsonLayer.ajaxParams.headers = axios.defaults.headers.common;
            this.geoJsonLayer.addTo(this.map);

            this.map.on('zoomstart', () => this.geoJsonLayer.clearLayers());
            this.map.on('moveend', () => this.geoJsonLayer.refresh(this.geoJson()));

            this.zoomControl().addTo(this.map);
            this.bookmarkControl().addTo(this.map);
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
            return this.unproject(
                this.planet.x, this.planet.y
            );
        },

        southWest() {
            return this.unproject(
                0, 0
            );
        },

        northEast() {
            return this.unproject(
                this.size, this.size
            );
        },

        unproject(x, y) {
            return this.map.unproject([
                x, y
            ], this.zoom);
        },

        multiplier() {
            return Math.pow(2, this.maxZoom - this.map.getZoom());
        },

        zoomControl() {
            return L.control.zoom({
                zoomInTitle: this.zoomInTitle,
                zoomOutTitle: this.zoomOutTitle
            });
        },

        bookmarkControl() {
            const BookmarkControl = L.Control.extend({
                options: {
                    position: 'topleft',
                    bookmarkTitle: this.bookmarkTitle,
                    bookmarkIconClass: 'icon-star'
                },

                onAdd() {
                    const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-bookmark');
                    const link = L.DomUtil.create('a', 'leaflet-control-bookmark', container);

                    link.href = '#';
                    link.title = this.options.bookmarkTitle;
                    link.onclick = e => {
                        e.preventDefault();
                        EventBus.$emit('bookmark-click');
                    };

                    L.DomUtil.create('i', this.options.bookmarkIconClass, link);

                    return container;
                }
            });

            return new BookmarkControl();
        }
    }
};
