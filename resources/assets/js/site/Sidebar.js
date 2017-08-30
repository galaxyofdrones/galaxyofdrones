export default {
    data() {
        return {
            isActive: false
        };
    },

    methods: {
        toggle() {
            this.isActive = !this.isActive;
        }
    }
};
