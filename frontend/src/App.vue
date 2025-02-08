<template>
    <AppNavbar />
    <div class="container mt-4">
        <router-view />
    </div>
</template>

<script>
import AppNavbar from './components/AppNavbar.vue'

export default {
    components: {
        AppNavbar
    }
}
</script>
