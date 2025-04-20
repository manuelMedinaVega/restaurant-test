<template>
    <v-container>
        <v-row class="justify-space-between align-center mb-4 mt-4">
            <h2>Gestión de Reservas</h2>
            <v-btn color="primary" @click="$router.push('/reservas/nueva')">Nueva Reserva</v-btn>
        </v-row>
  
        <v-data-table
            :items="reservations"
            :loading="loading"
            :page="page"
            :items-per-page="perPage"
            :items-length="total"
            class="elevation-1"
        >
            <template #headers>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>N° Personas</th>
                    <th>Comensal</th>
                    <th>Mesa</th>
                    <th>Acciones</th>
                </tr>
            </template>
  
            <template #item="{ item }">
                <tr>
                    <td>{{ item.id }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.time }}</td>
                    <td>{{ item.numberOfPeople }}</td>
                    <td>{{ item.clientName }}</td>
                    <td>{{ item.tableNumber }}</td>
                    <td>
                        <v-btn icon @click="editReservation(item.id)">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn icon @click="deleteReservation(item.id)">
                            <v-icon color="red">mdi-delete</v-icon>
                        </v-btn>
                    </td>
                </tr>
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
    const reservations = ref([])
    const page = ref(1)
    const perPage = ref(15)
    const total = ref(0)
    const loading = ref(false)
    const snackbar = ref({ show: false, message: '', color: 'success' })
    
    const fetchReservations = async () => {
        loading.value = true
        const response = await api.get('/api/v1/reservations', {
            params: { 
                page: page.value, 
                per_page: perPage.value 
            },
        })
        reservations.value = response.data.data.map(item => ({
            id: item.id,
            ...item.attributes,
            clientName: item.relationships?.client?.data.attributes.attributes.name || '—',
            tableNumber: item.relationships?.table?.data.attributes.attributes.number || '—',
        }))
        total.value = response.data.meta.total
        loading.value = false
    }
    
    const editReservation = (id) => {
        router.push(`/reservas/${id}/editar`)
    }
    
    const deleteReservation = async (id) => {
        if (confirm('¿Estás seguro de que deseas eliminar esta reserva?')) {
            await api.delete(`/api/v1/reservations/${id}`)
            showSnackbar('Reserva eliminada con éxito')
            fetchReservations()
        }
    }
    
    const showSnackbar = (msg, color = 'success') => {
        snackbar.value.message = msg
        snackbar.value.color = color
        snackbar.value.show = true
    }
    
    watch(page, fetchReservations)
    onMounted(fetchReservations)
</script>