import { defineStore } from 'pinia'
import { fetchNotifications, markAsRead } from '@/services/apiService'

const token = localStorage.getItem('token')

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        notifications: [],
        unreadCount: 0
    }),
    actions: {
        async fetchNotifications() {
            try {
                this.notifications = await fetchNotifications()
                this.unreadCount = this.notifications.length
                return this.notifications
            }
            catch (error) {
                console.error('Erro ao buscar notificações:', error)
            }
        },

        async markAsRead(id) {
            try {
                await markAsRead(id)
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
