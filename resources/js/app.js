import './bootstrap';
import {createApp} from "vue/dist/vue.esm-bundler";
import permissions from "./permissions.js";
import container from "../components/helpers/Container.vue";
import routes from "./routes.js";
import store from "./stores.js";

//register vue
const vueApp = createApp(container)

//auto register vue components
const components = import.meta.glob('../components/*.vue', {eager: true})
Object.entries(components).forEach(([path, definition]) => {
    const componentName = path.split('/').pop().replace(/\.\w+$/, '')
    vueApp.component(componentName, definition.default)
})

// Vuetify configuration
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'
import {createVuetify} from 'vuetify'
import * as VuetifyComponents from 'vuetify/components'
import * as VuetifyDirectives from 'vuetify/directives'
const vuetify = createVuetify({
    VuetifyComponents,
    VuetifyDirectives,
    ssr: true
})

vueApp.use(vuetify)
vueApp.use(store)
vueApp.use(permissions)
vueApp.use(routes)
vueApp.mount("#app")
