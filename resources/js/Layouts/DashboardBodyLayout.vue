<template>
    <perfect-scrollbar class="body__layout__ps">
        <slot />
        <div class="foot__shadow" v-if="!store.getters.loggedOut && auth.logged">&nbsp;</div>
    </perfect-scrollbar>
</template>
<script setup>
    import { useStore } from 'vuex'
    defineProps({
        auth: Object
    })
    const store = useStore()
</script>