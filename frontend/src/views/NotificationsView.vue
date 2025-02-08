<template>
    <div class="container mt-4">
        <h2>Notificações</h2>

        <div v-if="loading" class="text-center">
            <LoadingSpinner />
        </div>

        <div v-else>
            <div v-if="notifications.length === 0" class="alert alert-info">
                Nenhuma notificação encontrada
            </div>

            <div v-else class="list-group">
                <div v-for="notification in notifications" :key="notification.id" class="list-group-item"
                    :class="{ 'list-group-item-light': notification.read_at }">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1">{{ notification.data.message }}</p>
                            <small class="text-muted ms-2">{{ formatDate(notification.created_at) }}</small>
                        </div>

                        <button v-if="!notification.read_at" @click="markAsRead(notification)"
                            class="btn btn-sm btn-outline-primary">
                            Marcar como lida
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useNotificationStore } from '@/store/notification.store'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const notificationStore = useNotificationStore()
const notifications = ref([])
const loading = ref(true)

// Busca as notificações não lidas do usuário
const fetchNotifications = async () => {
    try {
        loading.value = true
        notifications.value = await notificationStore.fetchNotifications()
    } catch (error) {
        console.error("Catch-error", error)
    } finally {
        loading.value = false
    }
}

// Marca a notificação como lida
const markAsRead = async (notification) => {
    if (!notification.read_at) {
        await notificationStore.markAsRead(notification.id)
        notification.read_at = new Date().toISOString()
    }
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString()
}

onMounted(() => {
    fetchNotifications()
})
</script>

<style scoped>
.list-group-item {
    margin-bottom: 0.5rem;
    border-radius: 0.25rem;
}

.list-group-item-light {
    background-color: #f8f9fa;
}
</style>
