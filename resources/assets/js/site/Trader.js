import Cargo from './Cargo';
import Building from './Building';

export default Building.extend({
    props: ['grid', 'close', 'tradeTimeBonus', 'url', 'storeUrl', 'unitTypes'],

    mixins: [
        Cargo
    ],

    data() {
        return {
            hasTimer: true,
            data: {
                travel_time: 0
            }
        };
    },

    watch: {
        isEnabled() {
            this.quantity = {};
            this.fetchData();
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
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(this.url).then(
                response => this.data = response.data
            );
        },

        transport() {
            axios.post(this.storeUrl, {
                quantity: this.quantity
            }).then(this.close);
        }
    }
});
