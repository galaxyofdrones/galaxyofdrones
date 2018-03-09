import Remaining from './Remaining';

export default Remaining.extend({
    props: ['expedition', 'isCompletable', 'store'],

    created() {
        this.initRemaining(
            this.expedition.remaining
        );
    },

    watch: {
        expedition() {
            this.initRemaining(
                this.expedition.remaining
            );
        }
    }
});
