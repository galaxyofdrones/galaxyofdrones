import Remaining from './Remaining';

export default Remaining.extend({
    props: [
        'movement',
        'canMove',
        'move'
    ],

    created() {
        this.initRemaining(
            this.movement.remaining
        );
    },

    watch: {
        movement() {
            this.initRemaining(
                this.movement.remaining
            );
        }
    }
});
