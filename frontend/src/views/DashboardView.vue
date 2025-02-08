<template>
    <div>
        <div class="d-flex justify-content-between mb-4">
            <h2>Pedidos de Viagem</h2>
            <div class="d-flex small">
                <input v-model="filters.destino" type="text" class="form-control me-2" placeholder="Destino">
                <input v-model="filters.data_ida" type="date" class="form-control me-2">
                <input v-model="filters.data_volta" type="date" class="form-control me-2">
                <select v-model="statusFilter" class="form-select w-25">
                    <option value="">Todos</option>
                    <option>solicitado</option>
                    <option>aprovado</option>
                    <option>cancelado</option>
                </select>
                <button @click="applyFilters" class="btn btn-primary btn-sm ms-2">Filtrar</button>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Destino</th>
                    <th>Ida</th>
                    <th>Volta</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody v-if="loading">
                <tr>
                    <td colspan="6" class="text-center">
                        <LoadingSpinner />
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr v-for="order in ordersStore.orders" :key="order.id">
                    <td>{{ order.user.name ? order.user.name : '------' }}</td>
                    <td>{{ order.destino }}</td>
                    <td>{{ order.data_ida }}</td>
                    <td>{{ order.data_volta }}</td>
                    <td>
                        <span
                            :class="['badge', 'bg-' + (order.status === 'aprovado' ? 'success' : order.status === 'cancelado' ? 'danger' : 'info')]">{{
                                order.status }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="btnAction">
                            <button class="btn btn-info btn-sm" title="Visualizar Pedido" @click="viewOrder(order.id)">
                                <i class="pi pi-calendar"></i>
                            </button>
                            <button
                                v-if="(order.status !== 'aprovado' && order.status !== 'cancelado') && order.user.id != user_id"
                                class="btn btn-sm btn-success" @click="updateStatus(order.id, 'aprovado')"
                                :disabled="order.status === 'aprovado'" title="Aprovar Pedido">
                                <i class="pi pi-calendar-clock text-white" style="color: slateblue"></i>
                            </button>
                            <button v-if="order.status === 'aprovado' && order.user.id != user_id"
                                @click="cancelOrder(order.id)" class="btn btn-danger btn-sm"
                                :disabled="order.status === 'cancelado'" title="Cancelar Pedido">
                                <i class="pi pi-calendar-times"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation" class="d-flex justify-content-between align-items-start">
            <div class="text-center mt-2">
                Página {{ ordersStore.pagination.current_page }} de {{ ordersStore.pagination.last_page }} ( Total de
                registros {{ ordersStore.pagination.total }} )
            </div>
            <ul class="pagination justify-content-center">
                <li class="page-item" :class="{ disabled: ordersStore.pagination.current_page === 1 }">
                    <a class="page-link" href="#" @click.prevent="changePage(ordersStore.pagination.current_page - 1)">
                        << Anterior</a>
                </li>
                <li class="page-item" v-for="(link, index) in ordersStore.pagination.links" :key="index"
                    :class="{ active: link.active }" v-if="link && link.url">
                    <a class="page-link" href="#"
                        @click.prevent="changePage(new URL(link.url).searchParams.get('page'))">{{ link.label }}</a>
                </li>
                <li class="page-item"
                    :class="{ disabled: ordersStore.pagination.current_page === ordersStore.pagination.last_page }">
                    <a class="page-link" href="#"
                        @click.prevent="changePage(ordersStore.pagination.current_page + 1)">Próximo >></a>
                </li>
            </ul>

        </nav>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useTravelOrdersStore } from '@/store/travelOrders.store'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import { formatDate } from '@/utils/dateUtils'

const loading = ref(false)
const ordersStore = useTravelOrdersStore()
const statusFilter = ref('')
const filters = ref({
    destino: '',
    data_ida: '',
    data_volta: ''
})

const statusOptions = ['solicitado', 'aprovado', 'cancelado']
const user_id = localStorage.getItem('user_id') ? JSON.parse(localStorage.getItem('user_id')) : null

const router = useRouter()

// Filtra os pedidos de acordo com os filtros
// Desabilitado, e passado a responsabilidade do filtro para o backend
const filteredOrders = computed(() => {
    return ordersStore.orders.filter(order => {
        const matchesStatus = !statusFilter.value || order.status === statusFilter.value
        const matchesDestino = !filters.value.destino || order.destino.includes(filters.value.destino)
        const matchesDataIda = !filters.value.data_ida || order.data_ida >= filters.value.data_ida
        const matchesDataVolta = !filters.value.data_volta || order.data_volta <= filters.value.data_volta
        return matchesStatus && matchesDestino && matchesDataIda && matchesDataVolta
    })
})

onMounted(async () => {
    loading.value = true
    await ordersStore.fetchOrders()
    loading.value = false
})

// Atualiza a lista de pedidos
const updateOrdersList = async (page = 1) => {
    try {
        loading.value = true
        await ordersStore.fetchOrders({
            status: statusFilter.value,
            destino: filters.value.destino,
            data_ida: filters.value.data_ida,
            data_volta: filters.value.data_volta
        }, page)
    } catch (error) {
        console.error('Erro ao atualizar a lista de pedidos:', error)
    } finally {
        loading.value = false
    }
}

const applyFilters = async () => {
    await updateOrdersList()
}

const cancelOrder = async (id) => {
    try {
        loading.value = true
        await ordersStore.cancelOrder(id)
        await updateOrdersList()
    } catch (error) {
        console.error(error)
    } finally {
        loading.value = false
    }
}

// Atualiza o status do pedido
const updateStatus = async (id, status) => {
    try {
        loading.value = true
        await ordersStore.updateOrderStatus(id, status)
        await updateOrdersList()
    } catch (error) {
        toastr.error('Ocorreu um erro ao atualizar o status do pedido. Tente novamente!', 'Erro')
        console.error(error)
    } finally {
        loading.value = false
    }
}

const viewOrder = (id) => {
    router.push({ name: 'TravelOrderDetails', params: { id } })
}

const changePage = async (page) => {
    await updateOrdersList(page)
}
</script>
