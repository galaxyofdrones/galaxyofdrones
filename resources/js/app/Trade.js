import Transport from './Transport';

export default {
    props: [
        'isEnabled',
        'building',
        'grid',
        'close',
        'mined',
        'planet',
        'data',
        'storeUrl',
        'unitTypes'
    ],

    mixins: [
        Transport
    ],

    watch: {
        isEnabled() {
            this.quantity = {};
        }
    },

    computed: {
        travelTime() {
            return Math.round(
                this.data.travel_time / this.transporterUnit.speed * (1 - this.building.trade_time_bonus)
            );
        }
    },

    methods: {
        transport() {
            axios.post(this.storeUrl.replace('__grid__', this.grid.id), {
                quantity: this.quantity
            }).then(this.close);
        },

        unitQuantity(unit) {
            const storage = _.get(
                unit, 'storage', 0
            );

            return storage + _.get(
                unit, 'quantity', 0
            );
        }
    }
};
