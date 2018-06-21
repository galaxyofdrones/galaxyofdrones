/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./app/bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Bookmark from './app/Bookmark';
import Construction from './app/Construction';
import Demolish from './app/Demolish';
import Filters from './app/Filters';
import Mailbox from './app/Mailbox';
import Message from './app/Message';
import Monitor from './app/Monitor';
import Mothership from './app/Mothership';
import Move from './app/Move';
import Planet from './app/Planet';
import Player from './app/Player';
import Popover from './app/Popover';
import Profile from './app/Profile';
import Setting from './app/Setting';
import Sidebar from './app/Sidebar';
import Star from './app/Star';
import Starmap from './app/Starmap';
import Surface from './app/Surface';
import Trophy from './app/Trophy';
import Upgrade from './app/Upgrade';
import WhatsNew from './app/WhatsNew';

Vue.filter('bracket', Filters.bracket);
Vue.filter('fromNow', Filters.fromNow);
Vue.filter('item', Filters.item);
Vue.filter('number', Filters.number);
Vue.filter('percent', Filters.percent);
Vue.filter('sign', Filters.sign);
Vue.filter('timer', Filters.timer);

Vue.directive('popover', Popover);

const app = new Vue({
    el: '#app',

    components: {
        Bookmark,
        Construction,
        Demolish,
        Mailbox,
        Message,
        Monitor,
        Mothership,
        Move,
        Planet,
        Player,
        Profile,
        Setting,
        Sidebar,
        Star,
        Starmap,
        Surface,
        Trophy,
        Upgrade,
        WhatsNew
    }
});
