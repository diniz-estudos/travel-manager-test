import axios from 'axios'
import { API_BASE_URL } from '@/config'

const token = localStorage.getItem('token')

const apiClient = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        Authorization: `Bearer ${token}`
    }
})

// Lista as notificações não lidas do usuário
export const fetchNotifications = async () => {
    try {
        const response = await apiClient.get('/api/notifications')
        return response.data
    } catch (error) {
        console.error('Erro ao buscar notificações:', error)
        throw error
    }
}

// Marca uma notificação como lida
export const markAsRead = async (id) => {
    try {
        await apiClient.put(`/api/notifications/${id}`)
    } catch (error) {
        console.error('Erro ao marcar notificação como lida:', error)
        throw error
    }
}

// Lista os pedidos de viagem do usuário
export const fetchOrdersServices = async (filters = {}, page = 1) => {
    try {
        const response = await apiClient.get('/api/travel-orders', {
            params: { ...filters, page }
        })
        return response.data
    } catch (error) {
        console.error('Erro ao buscar pedidos:', error)
        throw error
    }
}

// Cria um novo pedido de viagem
export const createOrderServices = async (orderData) => {
    try {
        const response = await apiClient.post('/api/travel-orders', orderData)
        return response.data
    } catch (error) {
        console.error('Erro ao criar pedido:', error)
        throw error
    }
}

// Atualiza o status de um pedido de viagem
export const updateOrderStatusServices = async (orderId, status) => {
    try {
        const response = await apiClient.put(`/api/travel-orders/${orderId}/status`, { status })
        return response.data
    } catch (error) {
        console.error('Erro ao atualizar status do pedido:', error)
        throw error
    }
}

// Cancela um pedido de viagem
export const cancelOrderServices = async (orderId) => {
    try {
        const response = await apiClient.delete(`/api/travel-orders/${orderId}/cancel`)
        return response.data
    } catch (error) {
        console.error('Erro ao cancelar pedido:', error)
        throw error
    }
}

// Busca os detalhes de um pedido de viagem pelo ID
export const fetchOrderByIdServices = async (id) => {
    try {
        const response = await apiClient.get(`/api/travel-orders/${id}`)
        return response.data
    } catch (error) {
        console.error('Erro ao buscar detalhes do pedido:', error)
        throw error
    }
}
