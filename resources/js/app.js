import './bootstrap';
import '../css/app.css';
import '@vuepic/vue-datepicker/dist/main.css'
import 'flowbite';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import vSelect from "vue-select";
import VueDatePicker from '@vuepic/vue-datepicker';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// window.zohoOrgId = import.meta.env.VITE_ZOHO_WIDGET_ORG_ID;
// window.zohoAppId = import.meta.env.VITE_ZOHO_WIDGET_APP_ID;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("v-select", vSelect)
            .component('VueDatePicker', VueDatePicker)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
