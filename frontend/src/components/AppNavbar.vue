<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <router-link to="/" class="navbar-brand">
                <i class="pi pi-globe">&nbsp;</i>
                <span>Travel Management Test</span>
            </router-link>
            <div class="navbar-nav ms-auto">
                <router-link v-if="isAuthenticated" to="/" class="nav-link">
                    <i class="pi pi-home">&nbsp;</i>
                    <span>Home</span>
                </router-link>
                <router-link v-if="isAuthenticated" to="/travel-orders/new" class="nav-link">
                    <i class="pi pi-calendar-plus">&nbsp;</i>
                    <span>Nova Viagem</span>
                </router-link>
                <router-link v-if="!isAuthenticated" to="/login" class="nav-link">
                    Login
                </router-link>
                <div v-if="isAuthenticated" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="pi pi-user">&nbsp;</i>
                        {{ user_name }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <li>
                            <button @click="logout" class="dropdown-item">
                                <i class="pi pi-sign-out">&nbsp;</i>
                                Logout
                            </button>
                        </li>
                    </ul>
                </div>
                <div v-if="isAuthenticated" class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="pi pi-bell ms-2"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ unreadCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li v-if="loading">
                            <div class="px-4 py-2">
                                <LoadingSpinner small />
                            </div>
                        </li>
                        <li v-else>
                            <div v-if="notifications.length === 0" class="px-4 py-2">
                                <span class="small">Nenhuma notificação</span>
                            </div>
                            <template v-else>
                                <div class="dropdown-item" v-for="notification in notifications" :key="notification.id"
                                    @click="markAsRead(notification)">
                                    <div class="d-flex justify-content-between ms-1">
                                        <span>{{ notification.data.message }}</span>
                                        <small class="text-muted">{{ formatDate(notification.created_at) }}</small>
                                    </div>
                                </div>
                            </template>
                            <div class="dropdown-divider"></div>
                            <router-link to="/notifications" class="dropdown-item text-center">
                                Ver todas
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { useAuthStore } from '@/store/auth.store'
import { computed, ref, onMounted, onUnmounted} from 'vue'
import { useNotificationStore } from '@/store/notification.store'
import LoadingSpinner from '@/components/LoadingSpinner.vue'
import { formatDate } from '@/utils/dateUtils'

let intervalId

const authStore = useAuthStore()
const isAuthenticated = computed(() => authStore.isAuthenticated)
const user_name = computed(() => authStore.user_name)
const notificationStore = useNotificationStore()
const notifications = ref([])
const loading = ref(false)
const unreadCount = ref(0)

const logout = async () => {
    await authStore.logout()
}

const fetchNotifications = async () => {
    try {
        loading.value = true
        notifications.value = await notificationStore.fetchNotifications()
        updateUnreadCount()
    } finally {
        loading.value = false
    }
}

const updateUnreadCount = () => {
    unreadCount.value = notifications.value.filter(n => !n.read_at).length
}

const markAsRead = async (notification) => {
    if (!notification.read_at) {
        await notificationStore.markAsRead(notification.id)
        notification.read_at = new Date().toISOString()
        updateUnreadCount()
    }
}

onMounted(() => {
    if (authStore.isAuthenticated) {
        fetchNotifications()
        intervalId = setInterval(fetchNotifications, 60000)
    }
})

onUnmounted(() => {
    clearInterval(intervalId)
})
</script>

<style scoped>
.nav-link {
    cursor: pointer;
}

.badge {
    font-size: 0.75em;
    padding: 0.25em 0.5em;
}

.dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
    min-width: 650px;
    white-space: normal;
    word-wrap: break-word;
}

.dropdown-item {
    white-space: normal;
}

.dropdown-item:nth-child(odd) {
  border-bottom: 1px solid #e9ecef;
}

</style>
