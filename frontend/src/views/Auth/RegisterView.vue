<template>
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Criar Conta</h2>

                    <form @submit.prevent="submitForm">
                        <!-- Campo Nome -->
                        <div class="mb-3">
                            <label class="form-label">Nome Completo:</label>
                            <input v-model="form.name" type="text" class="form-control" required>
                        </div>

                        <!-- Campo Email -->
                        <div class="mb-3">
                            <label class="form-label">Email:</label>
                            <input v-model="form.email" type="email" class="form-control" required>
                        </div>

                        <!-- Campo Senha -->
                        <div class="mb-3">
                            <label class="form-label">Senha:</label>
                            <input v-model="form.password" type="password" class="form-control" required minlength="8">
                        </div>

                        <!-- Botão de Registro -->
                        <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                            {{ loading ? 'Registrando...' : 'Criar Conta' }}
                        </button>

                        <!-- Link para Login -->
                        <div class="mt-3 text-center">
                            Já tem uma conta? <router-link to="/login">Faça login</router-link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import toastr from 'toastr'

const form = ref({
    name: '',
    email: '',
    password: ''
})

const loading = ref(false)
const router = useRouter()

async function submitForm() {
    try {
        loading.value = true

        const response = await axios.post('/api/register', form.value)

        toastr.success('Conta criada com sucesso!')
        await nextTick(() => {
            router.push('/login')
        })

    } catch (error) {
        if (error.response?.data?.errors) {
            const errors = error.response.data.errors
            Object.keys(errors).forEach(key => {
                toastr.error(errors[key][0])
            })
        } else {
            toastr.error('Erro ao criar conta. Tente novamente.')
        }
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
.card {
    margin-top: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-control {
    border-radius: 4px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    padding: 10px;
    border-radius: 4px;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.text-center a {
    color: #007bff;
    text-decoration: none;
}

.text-center a:hover {
    text-decoration: underline;
}
</style>
