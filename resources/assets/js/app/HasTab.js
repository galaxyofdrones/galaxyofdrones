export default {
    data() {
        return {
            selectedTab: ''
        };
    },

    methods: {
        isSelectedTab(value) {
            return this.selectedTab === value;
        },

        selectTab(value) {
            this.selectedTab = value;
        }
    }
};
