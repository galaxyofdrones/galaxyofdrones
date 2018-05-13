import { EventBus } from './event-bus';

export default {
    props: [
        'size',
        'maxZoom',
        'geoJsonUrl',
        'tileUrl',
        'imagePath',
        'zoomInTitle',
        'zoomOutTitle',
        'bookmarkTitle'
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

            const coordsToLatLng = coords => this.map.unproject([
                coords[0], coords[1]
            ], this.maxZoom);

            this.geoJsonLayer = L.geoJson.ajax(this.geoJson(), {
                coordsToLatLng,

                pointToLayer: (geoJsonPoint, latLng) => {
                    if (geoJsonPoint.properties.isMovement) {
                        return this.movementMarker(
                            latLng, coordsToLatLng(geoJsonPoint.properties.end), geoJsonPoint
                        );
                    }

                    return this.objectMarker(latLng, geoJsonPoint);
                },

                style: feature => {
                    if (feature.geometry.type === 'LineString') {
                        return {
                            className: `leaflet-movement ${this.movementClassName(feature)}`
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
        },

        objectMarker(latLng, geoJsonPoint) {
            const size = (geoJsonPoint.properties.size + 16) / this.multiplier();

            const options = {
                className: geoJsonPoint.properties.type === 'planet'
                    ? `leaflet-icon-object ${geoJsonPoint.properties.status}`
                    : 'leaflet-icon-object',
                iconSize: [
                    size, size
                ]
            };

            if (this.map.getZoom() >= 8) {
                options.html = `<span>${geoJsonPoint.properties.name}</span>`;
            }

            const marker = L.marker(latLng, {
                title: geoJsonPoint.properties.name,
                icon: L.divIcon(options)
            });

            marker.on('click', () => EventBus.$emit(`${geoJsonPoint.properties.type}-click`, geoJsonPoint));

            return marker;
        },

        movementMarker(latLng, endLatLng, geoJsonPoint) {
            L.MovementMarker = L.Marker.extend({
                options: {
                    end: endLatLng,
                    interval: geoJsonPoint.properties.interval
                },

                onAdd(map) {
                    L.Marker.prototype.onAdd.call(this, map);

                    if (this._icon) {
                        this._icon.style[L.DomUtil.TRANSITION] = `all ${this.options.interval - 1}s linear`;
                    }

                    if (this._shadow) {
                        this._shadow.style[L.DomUtil.TRANSITION] = `all ${this.options.interval - 1}s linear`;
                    }

                    setTimeout(
                        () => this.setLatLng(this.options.end), 1000
                    );
                }
            });

            const size = 32 / this.multiplier();

            const options = {
                className: `leaflet-icon-movement ${this.movementClassName(geoJsonPoint)} size-${size}`,
                iconSize: [
                    size, size
                ]
            };

            if (this.map.getZoom() >= 8) {
                let angle = Math.atan2(endLatLng.lng - latLng.lng, endLatLng.lat - latLng.lat);
                let angleDeg = (angle > 0 ? angle : (2 * Math.PI + angle)) * 360 / (2 * Math.PI);

                options.html = `<i class="icon-movement-unit" style="${L.DomUtil.TRANSFORM}: translateX(-50%) translateY(-50%) rotate(${angleDeg}deg)"></i>`;
            }

            return new L.MovementMarker(latLng, {
                icon: L.divIcon(options)
            });
        },

        movementClassName(feature) {
            if (feature.properties.type === 'expedition') {
                return feature.properties.type;
            }

            if (feature.properties.type < 3) {
                return `${feature.properties.status}-attack`;
            }

            return feature.properties.status;
        }
    }
};
