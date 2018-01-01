import Vue from 'vue';

export default Vue.extend({
    data() {
        return {
            isLoading: true,
            loadingTimeout: undefined
        };
    },

    methods: {
        startLoading() {
            this.loadingTimeout = setTimeout(() => this.isLoading = true, 1000);
        },

        stopLoading() {
            if (!this.loadingTimeout) {
                return;
            }

            this.loadingTimeout = clearTimeout(this.loadingTimeout);
            this.isLoading = false;
        },

        scrollTo(element) {
            const $container = $('.content-body');
            const $scrollTo = $(element);

            $container.animate({
                scrollTop: $scrollTo.offset().top - $container.offset().top + $container.scrollTop()
            }, 300);
        }
    }
});
