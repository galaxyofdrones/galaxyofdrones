export default {
    methods: {
        isRouteName(name) {
            return !_.isEmpty(this.$route) && this.$route.name === name;
        }
    }
};
