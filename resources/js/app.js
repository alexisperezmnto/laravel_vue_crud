require('./bootstrap');

window.Vue = require('vue').default;

//Vuetify
import vuetify from '../plugins/vuetify'

//SweetAlert
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

Vue.use(VueSweetalert2)

//Router
import { routes } from './router/index'
import VueRouter from 'vue-router'

const router = new VueRouter({
    mode: 'history',
    hash: false,
    routes
})

//Store
import store from '../store/index'

import App from './App.vue'

const app = new Vue({
    router,
    store,
    vuetify,
    el: '#app',
    components: { App }
});
