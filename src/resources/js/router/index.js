import {createRouter, createWebHistory} from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import Login from '../components/views/Login.vue'
import Home from '../components/views/Home.vue'

import ClientList from '../components/views/clients/List.vue'
import ClientForm from '../components/views/clients/Form.vue'

import TableList from '../components/views/tables/List.vue'
import TableForm from '../components/views/tables/Form.vue'

import ReservationList from '../components/views/reservations/List.vue'
import ReservationForm from '../components/views/reservations/Form.vue'

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
        name: 'tables.list',
        component: TableList,
        meta: { requiresAuth: true }
    },
    {
        path: '/mesas/nueva',
        name: 'tables.new',
        component: TableForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/mesas/:id/editar',
        name: 'tables.edit',
        component: TableForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/reservas',
        name: 'reservations',
        component: ReservationList,
        meta: { requiresAuth: true }
    },
    {
        path: '/reservas/nueva',
        name: 'reservations.new',
        component: ReservationForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/reservas/:id/editar',
        name: 'reservations.edit',
        component: ReservationForm,
        meta: { requiresAuth: true }
    },
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