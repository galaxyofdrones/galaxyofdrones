import Echo from 'laravel-echo';

window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: window.location.port,
    wsPath: '/ws',
    disableStats: true,
    enabledTransports: ['ws', 'wss']
});

/**
 * We will import the required libs.
 */

window.Vue = require('vue');
window.Swal = require('sweetalert2');
window.moment = require('moment');

moment.locale(document.querySelector('html').getAttribute('lang'));

require('leaflet');
require('leaflet-ajax');
require('perfect-scrollbar');

/**
 * We will register the global error handling.
 */

window.axios.interceptors.response.use(null, error => {
    const status = _.get(error.response, 'status');

    if (status === 401 || status === 403) {
        window.location.reload();
    } else if (status === 500) {
        Swal.fire({
            title: Translations.error.whoops,
            text: Translations.error.wrong,
            type: 'error',
            showConfirmButton: false,
            timer: 1500
        });
    }

    return Promise.reject(error);
});
