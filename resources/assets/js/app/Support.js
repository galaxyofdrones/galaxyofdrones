export default {
    data() {
        return {
            hasTimer: false,
            quantity: {}
        };
    },

    computed: {
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

        slowestSupportUnitSpeed() {
            if (this.hasUnits) {
                const quantity = _.pickBy(this.quantity);

                return _.get(_.minBy(
                    _.filter(this.planet.units, unit => quantity.hasOwnProperty(unit.id)), 'speed'
                ), 'speed', 1);
            }

            return 1;
        }
    }
};
