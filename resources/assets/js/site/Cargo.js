import { EventBus } from '../common/event-bus';

export default {
    data() {
        return {
            hasTimer: false,
            mined: 0,
            quantity: {},
            planet: {
                id: undefined,
                resource_id: undefined,
                resources: [],
                units: []
            }
        };
    },

    created() {
        EventBus.$on('resource-updated', resource => this.mined = resource);
        EventBus.$on('planet-updated', planet => this.planet = planet);
    },

    computed: {
        canTransport() {
            return this.hasResources
                && this.transporterUnit.quantity > 0
                && this.transporterQuantity <= this.transporterUnit.quantity;
        },

        hasResources() {
            const quantity = _.pickBy(this.quantity);
            let hasResources = false;

            _.forEach(this.planet.resources, resource => {
                if (quantity.hasOwnProperty(resource.id)) {
                    return hasResources = quantity[resource.id] > 0 && quantity[resource.id] <= this.resourceQuantity(resource);
                }
            });

            return hasResources;
        },

        transporterUnit() {
            return _.find(
                this.planet.units, unit => unit.type === this.unitTypes.transporter
            );
        },

        transporterQuantity() {
            if (!this.hasResources) {
                return 0;
            }

            const totalResource = _.reduce(
                _.pickBy(this.quantity), (sum, quantity) => sum + quantity, 0
            );

            return Math.ceil(
                totalResource / this.transporterUnit.capacity
            );
        }
    },

    methods: {
        resourceQuantity(resource) {
            if (this.planet.resource_id === resource.id) {
                return Math.floor(this.mined);
            }

            return _.get(_.find(this.planet.resources, {
                id: resource.id
            }), 'quantity', 0);
        }
    }
};
