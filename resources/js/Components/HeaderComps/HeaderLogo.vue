<template>
    <div class="logo">
        <Link :href="route('root')">
            <img v-if="logoType === 'image'" src="MyApp" alt="Reminder Logo">
            <h1 v-else class="logo__text">MyReminder</h1>
        </Link>
    </div>
</template>
<script setup>
    defineProps({
        logoType: String
    })
</script>