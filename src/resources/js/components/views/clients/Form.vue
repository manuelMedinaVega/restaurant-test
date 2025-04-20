<template>
    <v-container>
        <v-card class="pa-6 max-w-xl mx-auto">
            <v-card-title>{{ isEdit ? 'Editar Comensal' : 'Nuevo Comensal' }}</v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleSubmit" ref="formRef">
                    <v-text-field 
                        label="Nombre" 
                        v-model="form.name" 
                        :rules="[v => !!v || 'El nombre es requerido']"
                        required />
                    <v-text-field 
                        label="Correo" 
                        v-model="form.email" 
                        :rules="[v => !!v || 'El correo es requerido', v => /.+@.+\..+/.test(v) || 'Formato inválido']"
                        required 
                        type="email" />

                    <v-text-field label="Teléfono" v-model="form.phone" />
                    
                    <v-textarea label="Dirección" v-model="form.address" />
        
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
            Comensal guardado con éxito
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
        name: '',
        email: '',
        phone: '',
        address: '',
    })
    
    const fetchClient = async () => {
        if (isEdit.value) {
            loading.value = true
            const response = await api.get(`/api/v1/clients/${id}`)
            const attributes = response.data.data.attributes
            form.name = attributes.name
            form.email = attributes.email
            form.phone = attributes.phone ?? ''
            form.address = attributes.address ?? ''
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
                    name: form.name,
                    email: form.email,
                    phone: form.phone,
                    address: form.address,
                }
            }
        }
        try {
            if (isEdit.value) {
                await api.put(`/api/v1/clients/${id}`, payload)
            } else {
                await api.post('/api/v1/clients', payload)
            }
            showSuccess.value = true
            setTimeout(() => router.push('/comensales'), 1500)
        } catch (err) {
            if (err.response && err.response.data.errors) {
                const errors = err.response.data.errors
                serverErrors.value = Object.values(errors).flat()
            } else {
                showSnackbar('Error al guardar comensal', 'error')
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
    
    onMounted(fetchClient)
</script>