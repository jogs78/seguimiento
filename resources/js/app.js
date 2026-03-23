import './bootstrap';
import '../css/login.css';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';


createInertiaApp({
    resolve: name => {
        //const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        //let page = pages[`./Pages/${name}.vue`]
        
        // Asignar el layout por defecto a todas las páginas
        //page.default.layout = page.default.layout || appLayout
        
        //return page
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
})

