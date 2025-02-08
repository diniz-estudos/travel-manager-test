import { nextTick } from 'vue'
import { defineStore } from 'pinia'
import axios from 'axios'
import router from '@/router'
import { API_BASE_URL } from '@/config'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user_name: localStorage.getItem('user_name') || null,
        user_email: localStorage.getItem('user_email') || null,
        user_id: localStorage.getItem('user_id') || null,
        token: localStorage.getItem('token') || null,
        tokenExpiration: localStorage.getItem('tokenExpiration') || null
    }),
    actions: {
        async login(credentials) {
            try {
                const response = await axios.post(`${API_BASE_URL}/api/login`, credentials,
                    {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    }
                )
                this.token = response.data.access_token
                this.user_name = response.data.user_name
                this.user_email = response.data.user_email
                this.user_id = response.data.user_id
                const expirationTime = new Date().getTime() + response.data.expires_in * 1000
                this.tokenExpiration = expirationTime

                localStorage.setItem('token', this.token)
                localStorage.setItem('tokenExpiration', expirationTime)
                localStorage.setItem('user_name', this.user_name)
                localStorage.setItem('user_email', this.user_email)
                localStorage.setItem('user_id', this.user_id)
                await nextTick(() => {
                    router.push('/')
                })
            } catch (error) {
                throw new Error('Erro desconhecido ao fazer login')
            }
        },
        async logout() {
            await axios.post(`${API_BASE_URL}/api/logout`)
            this.token = null
            this.tokenExpiration = null
            localStorage.removeItem('token')
            localStorage.removeItem('tokenExpiration')
            localStorage.removeItem('user_name')
            localStorage.removeItem('user_email')
            localStorage.removeItem('user_id')
            router.push('/login')
        },
        isTokenExpired() {
            const expirationTime = this.tokenExpiration
            if (!expirationTime) return true
            return new Date().getTime() > expirationTime
        }
    },
    getters: {
        isAuthenticated: (state) => !!state.token && !state.isTokenExpired()
    }
})
