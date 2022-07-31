import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css';
import 'v-calendar/dist/style.css';
import VCalendar from 'v-calendar';
import toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import store from './store'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const options = {
    position: "top-right",
    timeout: 7000,
    closeOnClick: false,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: false,
    draggablePercent: 0,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false,
    transition: "Vue-Toastification__bounce",
    maxToasts: 10,
    newestOnTop: true,
    filterBeforeCreate: (toast, toasts) => {
        if (toasts.filter(
        t => t.type === toast.type
        ).length !== 0) {
        return false;
        }
        return toast;
    }
};

createInertiaApp({
    title: (title) => `${appName} - ${title}`,
    resolve: async (name) => {
        let page = (await import(`./Pages/${name}.vue`)).default;

        //something goes here

        return page
    },
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .component("Head", Head)
            .component("Link", Link)
            .use(PerfectScrollbar)
            .use(VCalendar, {})
            .use(toast, options)
            .use(store)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#0088ff' });
