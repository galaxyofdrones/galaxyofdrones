import Support from './Support';

export default {
    props: [
        'isEnabled',
        'building',
        'grid',
        'close',
        'planet',
        'data',
        'storeUrl'
    ],

    mixins: [
        Support
    ],

    watch: {
        isEnabled() {
            this.quantity = {};
        }
    },

    computed: {
        travelTime() {
            return Math.round(
                this.data.travel_time / this.slowestSupportUnitSpeed * (1 - this.building.trade_time_bonus)
            );
        }
    },

    methods: {
        support() {
            axios.post(this.storeUrl.replace('__grid__', this.grid.id), {
                quantity: this.quantity
            }).then(this.close);
        }
    }
};
