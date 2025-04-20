<template>
    <v-container>
        <v-card class="pa-6 max-w-xl mx-auto">
            <v-card-title>{{ isEdit ? 'Editar Mesa' : 'Nueva Mesa' }}</v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleSubmit" ref="formRef">
                    <v-text-field 
                        label="Número" 
                        v-model="form.number" 
                        :rules="[v => !!v || 'El número es requerido']"
                        required />
                    <v-text-field 
                        label="Capacidad" 
                        v-model="form.capacity" 
                        :rules="[v => !!v || 'La capacidad es requerida']"
                        required 
                        type="email" />

                    <v-text-field label="Ubicación" v-model="form.location" />
                    
                    <v-btn color="primary" type="submit" class="mt-4" :loading="loading" block>
                        Guardar
                    </v-btn>
                </v-form>
            </v-card-text>
        </v-card>

        <v-alert v-if="serverErrors.length" type="error" class="mb-4" dense>
            <ul>
                <li v-for="(err, index) in serverErrors" :key="index">{{ err }}</li>
            </ul>
        </v-alert>

        <v-alert v-if="showSuccess" type="success" class="mb-4" dense>
            Mesa guardada con éxito
        </v-alert>

        <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="3000">
            {{ snackbar.message }}
        </v-snackbar>
    </v-container>
  </template>
  
  <script setup>
    import { ref, reactive, onMounted, computed } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import api from '@/axios'
    
    const route = useRoute()
    const router = useRouter()
    const id = route.params.id
    const isEdit = computed(() => !!id)
    
    const loading = ref(false)
    const snackbar = ref({ show: false, message: '', color: 'success' })
    const formRef = ref(null)

    const serverErrors = ref([])
    const showSuccess = ref(false)
    
    const form = reactive({
        number: '',
        capacity: '',
        location: ''
    })
    
    const fetchTable = async () => {
        if (isEdit.value) {
            loading.value = true
            const response = await api.get(`/api/v1/tables/${id}`)
            const attributes = response.data.data.attributes
            form.number = attributes.number
            form.capacity = attributes.capacity
            form.location = attributes.location ?? ''
            loading.value = false
        }
    }
    
    const handleSubmit = async () => {

        const valid = await formRef.value.validate()
        if (!valid) return

        loading.value = true
        const payload = {
            data: {
                attributes: {
                    number: form.number,
                    capacity: form.capacity,
                    location: form.location,
                }
            }
        }
        try {
            if (isEdit.value) {
                await api.put(`/api/v1/tables/${id}`, payload)
            } else {
                await api.post('/api/v1/tables', payload)
            }
            showSuccess.value = true
            setTimeout(() => router.push('/mesas'), 1500)
        } catch (err) {
            if (err.response && err.response.data.errors) {
                const errors = err.response.data.errors
                serverErrors.value = Object.values(errors).flat()
            } else {
                showSnackbar('Error al guardar mesa', 'error')
            }
        } finally {
            loading.value = false
        }
    }

    const showSnackbar = (msg, color = 'success') => {
        snackbar.value.message = msg
        snackbar.value.color = color
        snackbar.value.show = true
    }
    
    onMounted(fetchTable)
</script>