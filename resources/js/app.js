import Vue from 'vue';
import VueRouter from 'vue-router';
import Bookmark from './app/Bookmark';
import Construction from './app/Construction';
import Demolish from './app/Demolish';
import Donation from './app/Donation';
import Filters from './app/Filters';
import Mailbox from './app/Mailbox';
import Message from './app/Message';
import Monitor from './app/Monitor';
import Mothership from './app/Mothership';
import Move from './app/Move';
import Navigation from './app/Navigation';
import Planet from './app/Planet';
import Player from './app/Player';
import Popover from './app/Popover';
import Profile from './app/Profile';
import Routing from './app/Routing';
import Setting from './app/Setting';
import Sidebar from './app/Sidebar';
import Star from './app/Star';
import Starmap from './app/Starmap.vue';
import Surface from './app/Surface.vue';
import Trophy from './app/Trophy';
import Upgrade from './app/Upgrade';
import UpgradeAll from './app/UpgradeAll';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.filter('bracket', Filters.bracket);
Vue.filter('fromNow', Filters.fromNow);
Vue.filter('item', Filters.item);
Vue.filter('number', Filters.number);
Vue.filter('percent', Filters.percent);
Vue.filter('sign', Filters.sign);
Vue.filter('timer', Filters.timer);

Vue.directive('popover', Popover);

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',

    routes: [
        {
            path: '/starmap',
            name: 'starmap',
            component: Starmap,
            props: true
        },
        {
            path: '/',
            name: 'home',
            component: Surface,
            props: true
        }
    ]
});

const app = new Vue({
    router,

    components: {
        Bookmark,
        Construction,
        Demolish,
        Donation,
        Mailbox,
        Message,
        Monitor,
        Mothership,
        Move,
        Navigation,
        Planet,
        Player,
        Profile,
        Setting,
        Sidebar,
        Star,
        Trophy,
        Upgrade,
        UpgradeAll
    },

    mixins: [
        Routing
    ]
}).$mount('#app');
