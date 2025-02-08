import { defineStore } from 'pinia'
import axios from 'axios'
import { API_BASE_URL } from '@/config'

const token = localStorage.getItem('token')

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        unreadCount: 0
    }),
    actions: {
        async fetchNotifications() {
            try {
                const response = await axios.get(`${API_BASE_URL}/api/notifications`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                this.notifications = response.data
                this.unreadCount = this.notifications.length
                return this.notifications
            }
            catch (error) {
                console.error('Erro ao buscar notificações:', error)
            }
        },

        async markAsRead(id) {
            try {
                await axios.put(`${API_BASE_URL}/api/notifications/${id}`, {}, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                const notification = this.notifications.find(n => n.id === id)
                if (notification) {
                    notification.read_at = new Date().toISOString()
                    this.unreadCount--
                }
            } catch (error) {
                console.error('Erro ao marcar notificação como lida:', error)
            }
        }
    }
})
