<template>
    <v-container class="fill-height d-flex align-center justify-center">
        <v-card class="pa-6" max-width="500" width="100%">
            <v-card-title class="text-h5 font-weight-bold text-center">Iniciar Sesión</v-card-title>
    
            <v-card-text>
                <v-form @submit.prevent="login">
                    <v-text-field
                        label="Email"
                        v-model="email"
                        type="email"
                        required
                    />
                    <v-text-field
                        label="Contraseña"
                        v-model="password"
                        type="password"
                        required
                    />
                    <v-btn
                        type="submit"
                        color="indigo"
                        class="mt-4"
                        block
                        :loading="loading"
                    >
                        Entrar
                    </v-btn>
                    <v-alert
                        v-if="error"
                        type="error"
                        class="mt-4"
                        dense
                    >
                        {{ error }}
                    </v-alert>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
</template>
  
<script setup>
    import { ref } from 'vue'
    import { useRouter } from 'vue-router'
    import { useAuthStore } from '@/stores/auth'
    
    const router = useRouter()
    const auth = useAuthStore()

    const email = ref('')
    const password = ref('')
    const error = ref('')
    const loading = ref(false)
    
    const login = async () => {
        error.value = ''
        loading.value = true
        try {
            await auth.login(email.value, password.value)
            router.push('/')
        } catch (err) {
            error.value = 'Email o contraseña incorrectos'
        } finally {
            loading.value = false
        }
    }
</script>