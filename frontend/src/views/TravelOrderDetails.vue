<template>
    <div>
        <h2>Detalhes do Pedido de Viagem</h2>
        <div v-if="loading" class="text-center">
            <LoadingSpinner />
        </div>
        <div v-else>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info" role="alert">
                                <strong>Atenção!</strong> Este é um ambiente de demonstração, portanto, os pedidos de
                                viagem
                                não são reais.
                            </div>
                        </div>
                    </div>
                    <div class="row small">
                        <div class="col-md-4">
                            <span><strong>Pedido #</strong></span>
                        </div>
                        <div class="col-md-4">
                            <span><strong>Status</strong></span>
                        </div>
                        <div class="col-md-4">
                            <span><strong>Utlima atualização</strong></span>
                        </div>
                    </div>
                    <div class="row lead mb-3">
                        <div class="col-md-4 text-center">
                            <span><strong>{{ String(order.id).padStart(10, '0') }}</strong></span>
                        </div>
                        <div class="col-md-4">
                            <span
                                :class="['badge', 'bg-' + (order.status === 'aprovado' ? 'success' : order.status === 'cancelado' ? 'danger' : 'info')]">{{
                                    order.status }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <span>{{ formatDate(order.updated_at) }}</span>
                        </div>
                    </div>

                    <div class="row small">
                        <div class="col-md-4">
                            <span><strong>Usuário</strong></span>
                        </div>
                        <div class="col-md-4">
                            <span><strong>E-Mail</strong></span>
                        </div>
                        <div class="col-md-4">
                            <span><strong>Destino</strong></span>
                        </div>
                    </div>
                    <div class="row lead mb-3">
                        <div class="col-md-4">
                            <span>{{ order.user.name }}</span>
                        </div>
                        <div class="col-md-4">
                            <span>{{ order.user.email }}</span>
                        </div>
                        <div class="col-md-4">
                            <span>{{ order.destino }}</span>
                        </div>
                    </div>

                    <div class="row small">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span><strong>Data de Ida</strong></span>
                        </div>
                        <div class="col-md-4">
                            <span><strong>Data de Volta</strong></span>
                        </div>
                    </div>
                    <div class="row lead">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span>{{ formatDate(order.data_ida) }}</span>
                        </div>
                        <div class="col-md-4">
                            <span>{{ formatDate(order.data_volta) }}</span>
                        </div>
                    </div>
                    <div class="row small">
                        <div class="col-md-12">
                            <button class="btn btn-secondary" @click="goBack">Voltar</button>
                        </div>
                    </div>
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
