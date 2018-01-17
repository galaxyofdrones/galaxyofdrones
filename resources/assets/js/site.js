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

import Bookmark from './site/Bookmark';
import Construction from './site/Construction';
import Demolish from './site/Demolish';
import Filters from './site/Filters';
import Mailbox from './site/Mailbox';
import Mothership from './site/Mothership';
import Move from './site/Move';
import Planet from './site/Planet';
import Player from './site/Player';
import Popover from './site/Popover';
import Profile from './site/Profile';
import Setting from './site/Setting';
import Sidebar from './site/Sidebar';
import Star from './site/Star';
import Starmap from './site/Starmap';
import Surface from './site/Surface';
import Trophy from './site/Trophy';
import Upgrade from './site/Upgrade';

Vue.filter('bracket', Filters.bracket);
Vue.filter('fromNow', Filters.fromNow);
Vue.filter('item', Filters.item);
Vue.filter('number', Filters.number);
Vue.filter('percent', Filters.percent);
Vue.filter('sign', Filters.sign);
Vue.filter('timer', Filters.timer);

Vue.directive('popover', Popover);

const app = new Vue({
    el: '#site',

    components: {
        Bookmark,
        Construction,
        Demolish,
        Mailbox,
        Move,
        Mothership,
        Planet,
        Player,
        Profile,
        Setting,
        Sidebar,
        Star,
        Starmap,
        Surface,
        Trophy,
        Upgrade
    }
});
