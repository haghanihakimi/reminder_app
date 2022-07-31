<template>
    <Link :href="route('view.user', {reminder: props.notification.target})" target="_self">
        <div class="notification__icon">
            <div>
                <span class="material-symbols-rounded warning" v-if="props.notification.title === 'a reminder is close to deadline'">warning</span>
                <span class="material-symbols-rounded overdue" v-if="props.notification.title === 'a reminder is overdue now'">dangerous</span>
            </div>
        </div>
        <div class="notification__contents">
            <div class="notification__title">
                <span>{{props.notification.title}}</span>
            </div>
            <div class="notification__body">
                <p>
                    {{props.notification.message}}
                </p>
            </div>
        </div>
    </Link>
</template>
<script setup>
    const props = defineProps({
        notification: Object
    })
</script>