import Panel from './Panel';

export default Panel.extend({
    props: ['url'],

    data() {
        return {
            data: {
                items: []
            }
        };
    },

    created() {
        this.fetchData();
    },

    methods: {
        fetchData() {
            this.startLoading();
            axios.get(this.url).then(response => {
                this.data = response.data;
                this.stopLoading();
            });
        }
    }
});
