export default {
    props: ['isEnabled', 'url'],

    data() {
        return {
            data: {
                slots: [],
                units: []
            }
        };
    },

    computed: {
        isEmpty() {
            return !this.data.slots.length;
        }
    },

    watch: {
        isEnabled() {
            this.fetchData();
        }
    },

    methods: {
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(this.url).then(
                response => this.data = response.data
            );
        }
    }
};
