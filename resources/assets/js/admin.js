/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./common/bootstrap');
require('./admin/bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Filters from './admin/Filters';
import Overview from './admin/Overview';
import PerfectScrollbar from 'perfect-scrollbar';
import Random from './admin/Random';
import TableView from './admin/TableView';
import Tooltip from './admin/Tooltip';

Vue.filter('date', Filters.date);
Vue.filter('datetime', Filters.datetime);
Vue.filter('fromNow', Filters.fromNow);

Vue.directive('tooltip', Tooltip);

const app = new Vue({
    el: '#admin',

    components: {
        Overview, Random, TableView
    },

    data() {
        return {
            isSidebarActive: true,
            perfectScrollbar: undefined
        };
    },

    created() {
        $('.main-body', this.$el).removeClass('active');
    },

    mounted() {
        this.initSidebar();
    },

    methods: {
        initSidebar() {
            if (!this.$refs.sidebar) {
                return;
            }

            this.perfectScrollbar = new PerfectScrollbar(this.$refs.sidebar);

            $(this.$refs.sidebar)
                .on('shown.bs.collapse', this.perfectScrollbar.update)
                .on('hidden.bs.collapse', this.perfectScrollbar.update);
        },

        toggleSidebar() {
            this.isSidebarActive = !this.isSidebarActive;
            this.perfectScrollbar.update();
        }
    }
});
