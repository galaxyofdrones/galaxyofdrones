export default {
    data() {
        return {
            isMove: false,
            quantity: {}
        };
    },

    computed: {
        canTransport() {
            return this.hasResources && this.transporterQuantity <= this.unitQuantity(this.transporterUnit);
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
        setTotalResource(resource) {
            const total = this.resourceQuantity(resource);

            if (total > 0) {
                this.$set(this.quantity, resource.id, total);
            }
        },

        resourceQuantity(resource) {
            const storage = this.isMove
                ? _.get(resource, 'storage', 0)
                : 0;

            if (this.planet.resource_id === resource.id) {
                return storage + Math.floor(this.mined);
            }

            return storage + _.get(
                resource, 'quantity', 0
            );
        }
    }
};
