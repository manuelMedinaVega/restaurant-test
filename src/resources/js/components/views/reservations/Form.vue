<template>
    <v-container>
        <v-card class="pa-6 max-w-xl mx-auto">
            <v-card-title>{{ isEdit ? 'Editar Reserva' : 'Nueva Reserva' }}</v-card-title>
            <v-card-text>
                <v-form @submit.prevent="handleSubmit" ref="formRef">
                    <v-text-field
                        label="Fecha"
                        type="date"
                        v-model="form.date"
                        :rules="[v => !!v || 'La fecha es requerida']"/>
        
                    <v-text-field
                        label="Hora"
                        type="time"
                        v-model="form.time"
                        :rules="[v => !!v || 'La hora es requerida']"/>
            
                    <v-text-field
                        label="Número de personas"
                        type="number"
                        v-model="form.numberOfPeople"
                        :rules="[v => v > 0 || 'Debe ser mayor a 0']"/>
            
                    <v-select
                        label="Comensal"
                        :items="clients"
                        item-title="name"
                        item-value="id"
                        v-model="form.clientId"
                        :rules="[v => !!v || 'El cliente es requerido']"/>

                    <v-select
                        label="Mesa"
                        :items="tables"
                        item-title="number"
                        item-value="id"
                        v-model="form.tableId"
                        :rules="[v => !!v || 'La mesa es requerida']"/>
            
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
            Reserva guardada con éxito
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
        date: '',
        time: '',
        numberOfPeople: '',
        clientId: '',
        tableId: ''
    })

    const clients = ref([])
    const tables = ref([])
    
    const fetchClients = async () => {
        const res = await api.get('/api/v1/clients')
        clients.value = res.data.data.map(item => ({
            id: item.id,
            name: item.attributes.name
        }))
    }
    
    const fetchTables = async () => {
        const res = await api.get('/api/v1/tables')
        tables.value = res.data.data.map(item => ({
            id: item.id,
            number: item.attributes.number
        }))
    }
    
    const fetchReservation = async () => {

        if (isEdit.value) {
            loading.value = true
            const response = await api.get(`/api/v1/reservations/${id}`)
            const attributes = response.data.data.attributes
            const relationships = response.data.data.relationships
            form.date = attributes.date
            form.time = attributes.time
            form.numberOfPeople = attributes.numberOfPeople
            form.clientId = relationships.client.data.id
            form.tableId = relationships.table.data.id
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
                    date: form.date,
                    time: form.time,
                    number_of_people: form.numberOfPeople
                },
                relationships: {
                    client: {
                        data: {
                            id: form.clientId
                        }
                    },
                    table: {
                        data: {
                            id: form.tableId
                        }
                    }
                }
            }
        }

        try {
            if (isEdit.value) {
                await api.put(`/api/v1/reservations/${id}`, payload)
            } else {
                await api.post('/api/v1/reservations', payload)
            }
            showSuccess.value = true
            setTimeout(() => router.push('/reservas'), 1500)
        } catch (err) {
            if (err.response && err.response.data.errors) {
                const errors = err.response.data.errors
                serverErrors.value = Object.values(errors).flat()
            } else {
                showSnackbar('Error al guardar reserva', 'error')
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
    
    onMounted(() => {
        fetchClients()
        fetchTables()
        fetchReservation()
    })
</script>