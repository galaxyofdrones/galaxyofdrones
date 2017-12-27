import Research from './Research';

export default {
    props: ['isEnabled', 'url'],

    components: {
        Research
    },

    data() {
        return {
            data: {
                resource: {},
                units: []
            }
        };
    },

    computed: {
        isEmpty() {
            return !this.data.resource && !this.data.units.length;
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
        },

        storeResource(resource) {

        },

        destroyResource(resource) {

        },

        storeUnit(unit) {

        },

        destroyUnit(unit) {

        }
    }
};
