<template>
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</template>

<script setup>
</script>

<style scoped>
.spinner-border {
    width: 3rem;
    height: 3rem;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}
</style>
