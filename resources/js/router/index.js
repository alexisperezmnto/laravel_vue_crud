import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import Clientes from '../components/Clientes.vue'
import Cliente from '../components/Cliente.vue'

Vue.use(VueRouter)

export const routes = [
    {
        path: '/login',
        component: Login
    },
    {
        path: '/register',
        component: Register
    },
    {
        path: '/clientes',
        component: Clientes
    },
    {
        path: '/cliente/:id',
        component: Cliente
    },
    {
        path: '/',
        component: Login
    },
]