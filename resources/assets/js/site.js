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

import Player from './site/Player';
import Sidebar from './site/Sidebar';
import Starmap from './site/Starmap';
import Popover from './common/Popover';

Vue.directive('popover', Popover);

const app = new Vue({
    el: '#site',

    components: {
        Player, Sidebar, Starmap
    }
});
