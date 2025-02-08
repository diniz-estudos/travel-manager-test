import { defineStore } from 'pinia'
import {
    fetchOrdersServices,
    createOrderServices,
    updateOrderStatusServices,
    cancelOrderServices,
    fetchOrderByIdServices
} from '@/services/apiService'

export const useTravelOrdersStore = defineStore('travelOrders', {
    state: () => ({
        orders: [],
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 10,
            total: 0,
            links: []
        }
    }),
    actions: {
        async fetchOrders(filters = {}, page = 1) {
            try {
                const response = await fetchOrdersServices(filters, page)
                this.orders = response.data
                this.pagination = response.meta
                this.pagination.links = response.meta.links
            } catch (error) {
                console.error('Erro ao buscar pedidos de viagem:', error)
            }
        },
        async createOrder(orderData) {
            try {
                const newOrder = await createOrderServices(orderData)
            } catch (error) {
                console.error('Erro ao criar pedido de viagem:', error)
            }
        },
        async updateOrderStatus(orderId, status) {
            try {
                const response = await updateOrderStatusServices(orderId, status)
                const index = this.orders.findIndex(order => order.id === orderId)
                if (index !== -1) {
                    this.orders[index].status = response.status
                }
            } catch (error) {
                console.error('Erro ao atualizar status do pedido de viagem:', error)
            }
        },
        async cancelOrder(orderId) {
            try {
                const response = await cancelOrderServices(orderId)
                const index = this.orders.findIndex(order => order.id === orderId)
                if (index !== -1) {
                    this.orders[index].status = response.status
                }
            } catch (error) {
                console.error('Erro ao cancelar pedido de viagem:', error)
            }
        },
        async fetchOrderById(id) {
            try {
                const response = await fetchOrderByIdServices(id)
                return response.data
            } catch (error) {
                console.error('Erro ao buscar detalhes do pedido:', error)
                throw error
            }
        }
    }
})
