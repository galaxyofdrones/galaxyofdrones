import Remaining from './Remaining';

export default Remaining.extend({
    props: ['mission', 'isCompletable', 'store'],

    created() {
        this.initRemaining(
            this.mission.remaining
        );
    }
});
