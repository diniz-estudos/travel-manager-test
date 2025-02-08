<template>
    <div class="login-container">
        <h2>Login</h2>
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label for="email">Email</label>
                <input v-model="form.email" type="email" id="email" required />
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input v-model="form.password" type="password" id="password" required />
            </div>
            <button type="submit" :disabled="loading">
                {{ loading ? 'Aguarde validando credenciais...' : 'Entrar' }}
            </button>
            <p v-if="errorMessage" class="error-message">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/store/auth.store'

const form = ref({
    email: '',
    password: ''
})

const loading = ref(false)
const errorMessage = ref('')
const authStore = useAuthStore()

const submitForm = async () => {
    try {
        loading.value = true
        errorMessage.value = ''
        await authStore.login(form.value)
    } catch (error) {
        console.error('Erro ao fazer login:', error)
        errorMessage.value = 'Erro ao fazer login. Verifique suas credenciais e tente novamente.'
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 2rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 1rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
}

input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: white;
    font-size: 1rem;
    cursor: pointer;
}

button:disabled {
    background-color: #ccc;
}

.error-message {
    color: red;
    margin-top: 10px;
}
</style>
