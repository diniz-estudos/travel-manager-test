<template>
  <div v-if="showAlert && notifications.length" class="alert alert-info" role="alert">
    <ul>
        <button type="button" class="close" @click="showAlert = false">
      &times;
    </button>
      <li v-for="notification in notifications" :key="notification.id">
        {{ notification.data.message }}
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import { API_BASE_URL } from '@/config'
import { useAuthStore } from '@/store/auth.store'

const notifications = ref([])
const showAlert = ref(true)
const authStore = useAuthStore()

const fetchNotifications = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/api/notifications`, {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    })
    if (response.status === 200) {
      notifications.value = response.data
      return
    }
    notifications.value = []
  } catch (error) {
    console.error('Erro ao buscar notificações:', error)
  }
}

onMounted(async () => {
    if (authStore.isAuthenticated) {
        fetchNotifications()
    }
})

watch(() => authStore.isAuthenticated, (isAuthenticated) => {
  if (isAuthenticated) {
    fetchNotifications()
  } else {
    notifications.value = []
  }
})
</script>

<style scoped>
.alert {
  margin-top: 20px;
}
.close {
  background: none;
  border: none;
  font-size: 1.5rem;
  font-weight: bold;
  color: #000;
  position: absolute;
  top: 10px;
  right: 15px;
  cursor: pointer;
}
</style>
