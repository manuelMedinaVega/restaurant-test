<template>
    <v-app-bar app color="indigo" dark>

        <v-app-bar-title>Restaurante</v-app-bar-title>

        <v-spacer></v-spacer>

        <v-btn to="/" text>Inicio</v-btn>
        <v-btn to="/comensales" text>Comensales</v-btn>
        <v-btn to="/mesas" text>Mesas</v-btn>
        <v-btn to="/reservas" text>Reservas</v-btn>
        <v-btn v-if="isAuthenticated" @click="logout" color="red" variant="flat">Cerrar sesión</v-btn>

    </v-app-bar>
</template>

<script setup>
    import { useRouter } from 'vue-router'
    import { useAuthStore } from '@/stores/auth'
    import { computed } from 'vue'

    const router = useRouter()
    const auth = useAuthStore()

    const isAuthenticated = computed(() => auth.isAuthenticated)

    const logout = async () => {
        await auth.logout()
        router.push('/login')
    }
</script>