<template>
    <div class="container mt-4">
      <div v-if="loading">
        <LoadingSpinner />
      </div>

      <div v-else>
        <h2>Detalhes da Notificação</h2>

        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ notification.data.title }}</h5>
            <p class="card-text">{{ notification.data.message }}</p>

            <div class="text-muted small">
              Recebida em: {{ formatDate(notification.created_at) }}
            </div>

            <div v-if="notification.read_at" class="text-muted small">
              Lida em: {{ formatDate(notification.read_at) }}
            </div>

            <button
              v-if="!notification.read_at"
              @click="markAsRead"
              class="btn btn-primary mt-3"
            >
              Marcar como lida
            </button>
          </div>
        </div>

        <router-link to="/notifications" class="btn btn-link mt-3">
          Voltar para notificações
        </router-link>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue'
  import { useRoute } from 'vue-router'
  import axios from 'axios'
  import LoadingSpinner from '@/components/LoadingSpinner.vue'

  const route = useRoute()
  const notification = ref({})
  const loading = ref(true)

  const fetchNotification = async () => {
    try {
      const response = await axios.get(`/api/notifications/${route.params.id}`)
      notification.value = response.data
    } finally {
      loading.value = false
    }
  }

  const markAsRead = async () => {
    await axios.put(`/api/notifications/${route.params.id}/read`)
    notification.value.read_at = new Date().toISOString()
  }

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString()
  }

  onMounted(() => {
    fetchNotification()
  })
  </script>
