import { defineStore } from 'pinia'
import axios from 'axios'
import { API_BASE_URL } from '@/config'

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
                const token = localStorage.getItem('token') || null
                const response = await axios.get(`${API_BASE_URL}/api/travel-orders`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    params: { ...filters, page }
                })
                this.orders = response.data.data
                this.pagination = response.data.meta
                this.pagination.links = response.data.meta.links
            } catch (error) {
                console.error('Erro ao buscar pedidos de viagem:', error)
            }
        },
        async createOrder(orderData) {
            try {
                const token = localStorage.getItem('token')
                const response = await axios.post(`${API_BASE_URL}/api/travel-orders`, orderData, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                // this.orders.push(response.data)
            } catch (error) {
                console.error('Erro ao criar pedido de viagem:', error)
            }
        },
        async updateOrderStatus(orderId, status) {
            try {
                const token = localStorage.getItem('token')
                const response = await axios.put(`${API_BASE_URL}/api/travel-orders/${orderId}/status`, { status }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                const index = this.orders.findIndex(order => order.id === orderId)
                if (index !== -1) {
                    this.orders[index].status = response.data.status
                }
            } catch (error) {
                console.error('Erro ao atualizar status do pedido de viagem:', error)
            }
        },
        async cancelOrder(orderId) {
            try {
                const token = localStorage.getItem('token')
                const response = await axios.delete(`${API_BASE_URL}/api/travel-orders/${orderId}/cancel`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                const index = this.orders.findIndex(order => order.id === orderId)
                if (index !== -1) {
                    this.orders[index].status = response.data.status
                }
            } catch (error) {
                console.error('Erro ao cancelar pedido de viagem:', error)
            }
        },
        async fetchOrderById(id) {
            try {
              const response = await axios.get(`${API_BASE_URL}/api/travel-orders/${id}`,
                {
                  headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`
                  }
                }
              )
              return response.data.data
            } catch (error) {
              console.error('Erro ao buscar detalhes do pedido:', error)
              throw error
            }
          }
    }
})
