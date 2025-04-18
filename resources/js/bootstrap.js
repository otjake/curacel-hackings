/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Swal from 'sweetalert2';

window.Swal = Swal;

const successIcon = new URL(`../icons/success.svg`, import.meta.url).href;
const processingIcon = new URL(`../icons/processing.svg`, import.meta.url).href;
const errorIcon = new URL(`../icons/error.svg`, import.meta.url).href;

const SuccessAlert = Swal.mixin({
    iconColor: "#FFFFFF",
    iconHtml: `<img src='${successIcon}' alt='success'/>`,
    confirmButtonColor: "#1A1AFF",
})

const ProcessingAlert = Swal.mixin({
    iconColor: "#FFFFFF",
    iconHtml: `<img src='${processingIcon}' alt='processing'/>`,
    confirmButtonColor: "#1A1AFF",
})

const ErrorAlert = Swal.mixin({
    iconColor: "#FFFFFF",
    iconHtml: `<img src='${errorIcon}' alt='error'/>`,
    confirmButtonColor: "#1A1AFF",
})

window.SuccessAlert = SuccessAlert;
window.ProcessingAlert = ProcessingAlert;
window.ErrorAlert = ErrorAlert;

const SuccessToast = Swal.mixin({
    toast: true,
    icon: 'success',
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    width: "33em",
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

window.SuccessToast = SuccessToast;

const ErrorToast = Swal.mixin({
    toast: true,
    icon: 'error',
    position: 'top-end',
    showConfirmButton: false,
    width: "33em",
    timer: 4000,
})

window.ErrorToast = ErrorToast;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    wssPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
