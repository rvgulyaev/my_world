import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from 'pinia'
import mask from 'vue-the-mask';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import axios from 'axios';
import VueTailwindDatepicker from 'vue-tailwind-datepicker'

// проверка авторизации пользователя - если статус 401 - перенаправляем на страницу авторизации
axios.interceptors.response.use(function (response) {
    return response;
    }, function (error) {
    if(error.response.status === 401) {
        window.location.href = "/login";
    }
    return Promise.reject(error);
  });

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const pinia = createPinia()

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(mask)
            .use(Toast)
            .use(VueTailwindDatepicker)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
