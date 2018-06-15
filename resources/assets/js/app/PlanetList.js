import { EventBus } from './event-bus';
import CompletionLog from './CompletionLog';

export default CompletionLog.extend({
    props: [
        'canMove',
        'close'
    ],

    methods: {
        move(planet) {
            EventBus.$emit(
                'starmap-move', planet.x, planet.y
            );

            this.close();
        }
    }
});
