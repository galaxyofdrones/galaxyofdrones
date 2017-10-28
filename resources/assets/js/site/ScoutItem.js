import Remaining from './Remaining';

export default Remaining.extend({
    props: ['movement'],

    created() {
        this.initRemaining(
            this.movement.remaining
        );
    }
});
