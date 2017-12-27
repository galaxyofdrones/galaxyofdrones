import Remaining from './Remaining';

export default Remaining.extend({
    props: ['research', 'store', 'destroy'],

    data() {
        return {
            isResearch: true
        };
    },

    computed: {
        resource() {
            return this.research;
        },

        unit() {
            return this.research;
        }
    },

    created() {
        this.initRemaining(
            this.research.remaining
        );
    }
});
