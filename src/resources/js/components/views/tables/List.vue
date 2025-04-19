<template>
    <v-container>
        <v-row class="justify-space-between align-center mb-4 mt-4">
            <h2>Gestión de Mesas</h2>
            <v-btn color="primary" @click="$router.push('/mesas/nueva')">Nueva Mesa</v-btn>
        </v-row>
    
        <v-data-table
            :items="tables"
            :loading="loading" 
            :page.sync="page"
            :items-per-page="perPage"
            :server-items-length="total"
            class="elevation-1"
        >
            <template #headers>
                <tr>
                    <th>ID</th>
                    <th>Número</th>
                    <th>Capacidad</th>
                    <th>Ubicación</th>
                    <th>Acciones</th>
                </tr>
            </template>

            <template #item="{ item }">
                <tr>
                    <td>{{ item.id }}</td>
                    <td>{{ item.number }}</td>
                    <td>{{ item.capacity }}</td>
                    <td>{{ item.location }}</td>
                    <td>
                        <v-btn icon @click="editTable(item.id)">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn icon @click="deleteTable(item.id)">
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
    const tables = ref([])
    const page = ref(1)
    const perPage = ref(15)
    const total = ref(0)
    const loading = ref(false)
    const snackbar = ref({
        show: false,
        message: '',
        color: 'success'
    })
    
    const fetchTables = async () => {
        loading.value = true
        const response = await api.get('/api/v1/tables', {
            params: {
                page: page.value,
                per_page: perPage.value,
            },
        })
        tables.value = response.data.data.map(item => ({
            id: item.id,
            ...item.attributes
        }))
        total.value = response.data.meta.total
        loading.value = false
    }

    const editTable = (id) => {
        router.push(`/mesas/${id}/editar`)
    }
    
    const deleteTable = async (id) => {
        if (confirm('¿Estás seguro de que deseas eliminar esta mesa?')) {
            await api.delete(`/api/v1/tables/${id}`)
            showSnackbar('Mesa eliminada con éxito')
            fetchTables()
        }
    }

    const showSnackbar = (msg, color = 'success') => {
        snackbar.value.message = msg
        snackbar.value.color = color
        snackbar.value.show = true
    }
    
    watch(page, fetchTables)
    onMounted(fetchTables)
</script>