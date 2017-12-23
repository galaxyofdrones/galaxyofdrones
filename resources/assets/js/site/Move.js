import { EventBus } from '../common/event-bus';
import Modal from './Modal';

export default Modal.extend({
    props: ['types', 'unitTypes', 'urls'],

    data() {
        return {
            type: undefined,
            mined: 0,
            quantity: {},
            selected: {
                id: undefined,
                travel_time: 0
            },
            planet: {
                id: undefined,
                units: []
            }
        };
    },

    created() {
        EventBus.$on('move-click', this.open);
        EventBus.$on('resource-updated', resource => this.mined = resource);
        EventBus.$on('planet-updated', planet => this.planet = planet);
    },

    computed: {
        isScoutType() {
            return this.type === this.types.scout;
        },

        isAttackType() {
            return this.type === this.types.attack;
        },

        isOccupyType() {
            return this.type === this.types.occupy;
        },

        isSupportType() {
            return this.type === this.types.support;
        },

        isTransportType() {
            return this.type === this.types.transport;
        },

        canTransport() {
            return this.hasResources
                && this.transporterUnit.quantity > 0
                && this.transporterQuantity <= this.transporterUnit.quantity;
        },

        canScout() {
            return this.quantity.hasOwnProperty(this.scoutUnit.id)
                && this.quantity[this.scoutUnit.id] > 0
                && this.quantity[this.scoutUnit.id] <= this.scoutUnit.quantity;
        },

        canOccupy() {
            return this.settlerUnit.quantity > 0;
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

        hasUnits() {
            const quantity = _.pickBy(this.quantity);
            let hasUnits = false;

            _.forEach(this.planet.units, unit => {
                if (quantity.hasOwnProperty(unit.id)) {
                    return hasUnits = quantity[unit.id] > 0 && quantity[unit.id] <= unit.quantity;
                }
            });

            return hasUnits;
        },

        hasFighterUnits() {
            const quantity = _.pickBy(this.quantity);
            let hasFighterUnits = false;

            _.forEach(this.fighterUnits, unit => {
                if (quantity.hasOwnProperty(unit.id)) {
                    return hasFighterUnits = quantity[unit.id] > 0 && quantity[unit.id] <= unit.quantity;
                }
            });

            return hasFighterUnits;
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
        },

        scoutUnit() {
            return _.find(
                this.planet.units, unit => unit.type === this.unitTypes.scout
            );
        },

        settlerUnit() {
            return _.find(
                this.planet.units, unit => unit.type === this.unitTypes.settler
            );
        },

        fighterUnits() {
            return _.filter(
                this.planet.units, unit => unit.type === this.unitTypes.fighter || unit.type === this.unitTypes.heavyFighter
            );
        },

        travelTime() {
            return Math.round(
                this.selected.travel_time / this.slowestUnitSpeed
            );
        },

        slowestUnitSpeed() {
            if (this.isScoutType) {
                return this.scoutUnit.speed;
            }

            if (this.isOccupyType) {
                return this.settlerUnit.speed;
            }

            if (this.isTransportType) {
                return this.transporterUnit.speed;
            }

            if (this.isSupportType && this.hasUnits) {
                const quantity = _.pickBy(this.quantity);

                return _.get(_.minBy(
                    _.filter(this.planet.units, unit => quantity.hasOwnProperty(unit.id)), 'speed'
                ), 'speed', 1);
            }

            if (this.isAttackType && this.hasFighterUnits) {
                const quantity = _.pickBy(this.quantity);

                return _.get(_.minBy(
                    _.filter(this.fighterUnits, unit => quantity.hasOwnProperty(unit.id)), 'speed'
                ), 'speed', 1);
            }

            return 1;
        }
    },

    methods: {
        open(type, selected) {
            this.type = type;
            this.selected = selected;
            this.quantity = {};
            this.$nextTick(() => this.$modal.modal());
        },

        scout() {
            axios.post(this.urls.scout.replace('__planet__', this.selected.id), {
                quantity: this.quantity[this.scoutUnit.id]
            }).then(
                () => this.$modal.modal('hide')
            );
        },

        attack() {
            axios.post(this.urls.attack.replace('__planet__', this.selected.id), {
                quantity: this.quantity
            }).then(
                () => this.$modal.modal('hide')
            );
        },

        occupy() {
            axios.post(
                this.urls.occupy.replace('__planet__', this.selected.id)
            ).then(
                () => this.$modal.modal('hide')
            );
        },

        support() {
            axios.post(this.urls.support.replace('__planet__', this.selected.id), {
                quantity: this.quantity
            }).then(
                () => this.$modal.modal('hide')
            );
        },

        transport() {
            axios.post(this.urls.transport.replace('__planet__', this.selected.id), {
                quantity: this.quantity
            }).then(
                () => this.$modal.modal('hide')
            );
        },

        resourceQuantity(resource) {
            if (this.planet.resource_id === resource.id) {
                return Math.floor(this.mined);
            }

            return _.get(_.find(this.planet.resources, {
                id: resource.id
            }), 'quantity', 0);
        }
    }
});
