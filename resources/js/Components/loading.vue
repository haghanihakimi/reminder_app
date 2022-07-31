<template>
    <div class="loading">
        <svg viewBox="0 0 698 698" :style="{width: `${width}px`, height: `${height}px`}">
            <circle cx="349" cy="349" r="319" :stroke="color"/>
        </svg>
    </div>
</template>
<script setup>

    const props = defineProps({
        width: Number,
        height: Number,
        color: String
    })
</script>
<style scoped>
    @import "/css/loading.css";
</style>