import Remaining from './Remaining';

export default Remaining.extend({
    props: [
        'completion',
        'isCompletable',
        'store'
    ],

    created() {
        this.initRemaining(
            this.completion.remaining
        );
    },

    watch: {
        completion() {
            this.initRemaining(
                this.completion.remaining
            );
        }
    }
});
