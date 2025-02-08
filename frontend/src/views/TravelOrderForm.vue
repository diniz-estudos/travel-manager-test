<template>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Novo Pedido de Viagem</h5>
            <form @submit.prevent="submitForm">
                <div class="mb-3">
                    <label class="form-label">Destino</label>
                    <input v-model.trim="form.destino" type="text" class="form-control" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Data Ida</label>
                        <input v-model="form.data_ida" type="date" class="form-control" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Data Volta</label>
                        <input v-model="form.data_volta" type="date" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" :disabled="loading">
                    <span v-if="loading" class="spinner-border spinner-border-sm"></span>
                    {{ loading ? 'Enviando...' : 'Criar Pedido' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useTravelOrdersStore } from '@/store/travelOrders.store'
import toastr from 'toastr'

const form = ref({
    destino: '',
    data_ida: '',
    data_volta: '',
    purpose: ''
})

const ordersStore = useTravelOrdersStore()
const loading = ref(false)
const router = useRouter()

const submitForm = async () => {
    try {
        loading.value = true
        const newOrder = await ordersStore.createOrder(form.value)
        form.value = {}
        toastr.success('Pedido de viagem criado com sucesso!', 'Sucesso')
        router.push({ name: 'TravelOrderDetails', params: { id: newOrder.id } })
    } catch (error) {
        toastr.error('Ocorreu um erro ao criar o pedido. Tente novamente!', 'Erro')
    } finally {
        loading.value = false
    }
}
</script>
