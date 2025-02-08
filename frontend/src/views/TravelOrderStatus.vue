<template>
    <div>
      <div class="d-flex justify-content-between mb-4">
        <h2>Status dos Pedidos de Viagem</h2>
      </div>
      <NotificationAlert />
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Destino</th>
            <th>Ida</th>
            <th>Volta</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody v-if="loading">
          <tr>
            <td colspan="5" class="text-center">
              <LoadingSpinner />
            </td>
          </tr>
        </tbody>
        <tbody v-else>
          <tr v-for="order in filteredOrders" :key="order.id">
            <td>{{ order.destino }}</td>
            <td>{{ formatDate(order.data_ida) }}</td>
            <td>{{ formatDate(order.data_volta) }}</td>
            <td>
              <select v-model="order.status" @change="updateOrderStatus(order)" class="form-select">
                <option value="solicitado">Solicitado</option>
                <option value="aprovado">Aprovado</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </td>
            <td>
              <button v-if="order.status === 'aprovado'" @click="cancelOrder(order.id)" class="btn btn-danger btn-sm">
                Cancelar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script setup>
  import { ref, computed, onMounted } from 'vue'
  import { useTravelOrdersStore } from '@/store/travelOrders.store'
  import LoadingSpinner from '@/components/LoadingSpinner.vue'
  import NotificationAlert from '@/components/NotificationAlert.vue'
  import { formatDate } from '@/utils/dateUtils'

  const ordersStore = useTravelOrdersStore()
  const loading = ref(false)

  onMounted(async () => {
    loading.value = true
    await ordersStore.fetchOrders(aprovaco=true)
    loading.value = false
  })

  const updateOrderStatus = async (order) => {
    await ordersStore.updateOrderStatus(order.id, order.status)
  }

  const cancelOrder = async (id) => {
    await ordersStore.cancelOrder(id)
  }
  </script>
