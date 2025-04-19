<template>
    <v-container class="mt-5">
        <v-card class="mx-auto" max-width="400">
            <v-card-title>Iniciar sesión</v-card-title>
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
                    <v-btn type="submit" color="primary" block>Entrar</v-btn>
                </v-form>
            </v-card-text>
        </v-card>
    </v-container>
  </template>
  
  <script setup>

  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  
  const email = ref('')
  const password = ref('')
  const router = useRouter()
  
  const login = async () => {
    try {

        await axios.get('/sanctum/csrf-cookie')

        await axios.post('/api/login', {
            email: email.value,
            password: password.value,
        })

        router.push('/')
    } catch (err) {
        alert('Credenciales inválidas')
    }
  }
  </script>