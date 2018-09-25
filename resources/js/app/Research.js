import Remaining from './Remaining';

export default Remaining.extend({
    props: [
        'research',
        'isResearchable',
        'store',
        'destroy'
    ],

    data() {
        return {
            isResearch: true
        };
    },

    created() {
        this.initRemaining(
            this.research.remaining
        );
    },

    computed: {
        resource() {
            return this.research;
        },

        unit() {
            return this.research;
        }
    },

    watch: {
        research() {
            this.initRemaining(
                this.research.remaining
            );
        }
    }
});
