import {createRouter, createWebHistory} from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import Login from '../components/views/Login.vue'
import Home from '../components/views/Home.vue'

import ClientList from '../components/views/clients/List.vue'
import ClientForm from '../components/views/clients/Form.vue'

import Tables from '../components/views/Tables.vue'

import Reservations from '../components/views/Reservations.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    { 
        path: '/', 
        name: 'home', 
        component: Home,
        meta: { requiresAuth: true }
    },
    { 
        path: '/comensales',
        name: 'clients.list',
        component: ClientList,
        meta: { requiresAuth: true }
    },
    { 
        path: '/comensales/nuevo',
        name: 'clients.new',
        component: ClientForm,
        meta: { requiresAuth: true }
    },
    { 
        path: '/comensales/:id/editar',
        name: 'clients.edit',
        component: ClientForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/mesas',
        name: 'tables',
        component: Tables,
        meta: { requiresAuth: true }
    },
    {
        path: '/reservas',
        name: 'reservations',
        component: Reservations,
        meta: { requiresAuth: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const auth = useAuthStore()
    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        next('/login')
    } else {
        next()
    }
})

export default router