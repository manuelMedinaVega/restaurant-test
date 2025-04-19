<template>
    <v-container>
        <v-row class="justify-space-between align-center mb-4">
            <h2>Gestión de Comensales</h2>
            <v-btn color="primary" @click="$router.push('/comensales/nuevo')">Nuevo Comensal</v-btn>
        </v-row>
    
        <v-data-table
            :headers="headers"
            :items="clients"
            :loading="loading" 
            :page.sync="page"
            :items-per-page="perPage"
            :server-items-length="total"
            class="elevation-1"
        >
            <template #item.actions="{ item }">
                <v-btn icon @click="editClient(item.id)">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn icon @click="deleteClient(item.id)">
                    <v-icon color="red">mdi-delete</v-icon>
                </v-btn>
            </template>
        </v-data-table>

        <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="3000">
            {{ snackbar.message }}
        </v-snackbar>
    </v-container>
  </template>
  
  <script setup>
    import { ref, onMounted, watch } from 'vue'
    import api from '@/axios'
    import { useRouter } from 'vue-router'
    
    const router = useRouter()
    const clients = ref([])
    const page = ref(1)
    const perPage = ref(15)
    const total = ref(0)
    const loading = ref(false)
    const snackbar = ref({
        show: false,
        message: '',
        color: 'success'
    })
    
    const headers = [
        { text: 'Nombre', value: 'name' },
        { text: 'Correo', value: 'email' },
        { text: 'Teléfono', value: 'phone' },
        { text: 'Dirección', value: 'address' },
        { text: 'Acciones', value: 'actions', sortable: false },
    ]
    
    const fetchClients = async () => {
        loading.value = true
        const response = await api.get('/api/v1/clients', {
            params: {
                page: page.value,
                per_page: perPage.value,
            },
        })
        clients.value = response.data.data.map(item => ({
            id: item.id,
            ...item.attributes
        }))
        total.value = response.data.meta.total
        loading.value = false
    }

    const editClient = (id) => {
        router.push(`/comensales/${id}/editar`)
    }
    
    const deleteClient = async (id) => {
        if (confirm('¿Estás seguro de que deseas eliminar este comensal?')) {
            await api.delete(`/api/v1/clients/${id}`)
            showSnackbar('Comensal eliminado con éxito')
            fetchClients()
        }
    }

    const showSnackbar = (msg, color = 'success') => {
        snackbar.value.message = msg
        snackbar.value.color = color
        snackbar.value.show = true
    }
    
    watch(page, fetchClients)
    onMounted(fetchClients)
  </script>