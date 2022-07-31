<template>
    <div class="register__layout">
        <div class="background__layer">
            <img src="/storage/signup_bg.jpg" alt="Background Image">
        </div>
        <slot />
    </div>
</template>
<script setup>
    defineProps({
    })
</script>
<style scoped>
    @import "/css/RegistrationPage.css";
</style>