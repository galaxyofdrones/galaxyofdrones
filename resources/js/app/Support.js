export default {
    data() {
        return {
            isMove: false,
            quantity: {}
        };
    },

    computed: {
        hasUnits() {
            const quantity = _.pickBy(this.quantity);
            let hasUnits = false;

            _.forEach(this.planet.units, unit => {
                if (_.has(quantity, unit.id)) {
                    hasUnits = quantity[unit.id] > 0;

                    return hasUnits && quantity[unit.id] <= this.unitQuantity(unit);
                }

                return true;
            });

            return hasUnits;
        },

        slowestSupportUnitSpeed() {
            if (this.hasUnits) {
                const quantity = _.pickBy(this.quantity);

                return _.get(_.minBy(
                    _.filter(this.planet.units, unit => _.has(quantity, unit.id)), 'speed'
                ), 'speed', 1);
            }

            return 1;
        }
    },

    methods: {
        setTotalUnit(unit) {
            const total = this.unitQuantity(unit);

            if (total > 0) {
                this.$set(this.quantity, unit.id, total);
            }
        },

        unitQuantity(unit) {
            const storage = this.isMove
                ? _.get(unit, 'storage', 0)
                : 0;

            return storage + _.get(
                unit, 'quantity', 0
            );
        }
    }
};
