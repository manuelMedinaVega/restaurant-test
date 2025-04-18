import {createRouter, createWebHistory} from 'vue-router'

import Home from '../components/views/Home.vue'
import Clients from '../components/views/Clients.vue'
import Tables from '../components/views/Tables.vue'
import Reservations from '../components/views/Reservations.vue'

const routes = [
    { 
        path: '/', 
        name: 'home', 
        component: Home 
    },
    { 
        path: '/comensales',
        name: 'clients',
        component: Clients 
    },
    {
        path: '/mesas',
        name: 'tables',
        component: Tables
    },
    {
        path: '/reservas',
        name: 'reservations',
        component: Reservations
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router