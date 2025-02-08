<template>
    <div>
        <h2>Detalhes do Pedido de Viagem</h2>
        <div v-if="loading" class="text-center">
            <LoadingSpinner />
        </div>
        <div v-else>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pedido #{{ order.id }}</h5>
                    <p><strong>Usuário:</strong> {{ order.user.name }}</p>
                    <p><strong>Destino:</strong> {{ order.destino }}</p>
                    <p><strong>Data de Ida:</strong> {{ formatDate(order.data_ida) }}</p>
                    <p><strong>Data de Volta:</strong> {{ formatDate(order.data_volta) }}</p>
                    <p><strong>Status:</strong> <span
                            :class="['badge', 'bg-' + (order.status === 'aprovado' ? 'success' : order.status === 'cancelado' ? 'danger' : 'info')]">{{
                            order.status }}</span></p>
                    <button class="btn btn-secondary" @click="goBack">Voltar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useTravelOrdersStore } from '@/store/travelOrders.store'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import { formatDate } from '@/utils/dateUtils'

const route = useRoute()
const router = useRouter()
const ordersStore = useTravelOrdersStore()
const loading = ref(true)
const order = ref(null)

// Busca os detalhes do pedido de viagem pelo ID
const fetchOrderDetails = async () => {
    try {
        const orderId = route.params.id
        order.value = await ordersStore.fetchOrderById(orderId)
    } catch (error) {
        console.error('Erro ao buscar detalhes do pedido:', error)
    } finally {
        loading.value = false
    }
}

onMounted(fetchOrderDetails)

const goBack = () => {
    router.push({ name: 'Dashboard' })
}
</script>
