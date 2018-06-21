import { EventBus } from './event-bus';
import CompletionLog from './CompletionLog';

export default CompletionLog.extend({
    props: [
        'username',
        'canMove',
        'close'
    ],

    computed: {
        dataUrl() {
            return this.url.replace('__user__', this.username);
        }
    },

    methods: {
        move(planet) {
            EventBus.$emit(
                'starmap-move', planet.x, planet.y
            );

            this.close();
        }
    }
});
