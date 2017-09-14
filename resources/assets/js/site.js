/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./common/bootstrap');
require('./site/bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Filters from './site/Filters';
import Player from './site/Player';
import Popover from './common/Popover';
import Sidebar from './site/Sidebar';
import Starmap from './site/Starmap';

Vue.directive('popover', Popover);
Vue.filter('sign', Filters.sign);
Vue.filter('number', Filters.number);
Vue.filter('timer', Filters.timer);

const app = new Vue({
    el: '#site',

    components: {
        Player, Sidebar, Starmap
    }
});
