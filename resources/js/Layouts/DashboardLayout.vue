<template>
    <div class="dashboard__container">
        <slot />
    </div>
</template>
<script setup>
    import { onMounted  } from 'vue'
    import { useStore } from 'vuex'

    const props = defineProps({
        auth: Object,
    })

    const store = useStore()


</script>
<style scoped>
    @import "/css/Dashboard.css";
</style>