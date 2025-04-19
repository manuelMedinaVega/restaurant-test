import { defineStore } from 'pinia'
import api from '@/axios'

export const useAuthStore = defineStore('auth', {
    persist: true,
    state: () => ({
        token: null,
    }),

    getters: {
        isAuthenticated: (state) => !!state.token,
    },

    actions: {
        async login(email, password) {
            const response = await api.post('api/login', { email, password })
            this.token = response.data.data.token
        },
        async logout() {
            try {
                await api.post('api/logout')
            } catch (e) {

            }
            this.token = null
        },
    },
})