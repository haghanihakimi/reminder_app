<template>
    <div class="dash__leftPanel">
        <perfect-scrollbar class="leftPanel__layout__ps">
            <RemindersHeader :auth="auth" :comings="comings" :deadlines="deadlines" :overdue="overdue"/>
            <RemindersList :auth="auth"/>
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { computed, onMounted  } from 'vue'
    import { useStore } from 'vuex'
    import { useToast } from "vue-toastification";
    import "vue-toastification/dist/index.css";
    import moment from 'moment'
    import { Inertia } from '@inertiajs/inertia'
    import RemindersHeader from './RemindersHeader.vue'
    import RemindersList from './RemindersList.vue'

    const props = defineProps({
        auth: Object,
    })

    const store = useStore()

    const toast = useToast()

    const comings = computed(() => {
        return store.getters.remindersList.filter(reminder => moment(reminder.due).diff(moment(), 'minutes') > 4320).length
    })

    const deadlines = computed(() => {
        return store.getters.remindersList.filter(reminder => moment(reminder.due).diff(moment(), 'minutes') <= 4320 && moment(reminder.due).diff(moment(), 'minutes') >= 0).length
    })

    const overdue = computed(() => {
        return store.getters.remindersList.filter(reminder => moment(reminder.due).diff(moment(), 'minutes') < 0).length
    })

    const notifications = () => {
        Echo.private(`notification.${props.auth.user.data.id}`)
            .listen('NotificationsPops', (response) => {
                toast.error(response.message, store.getters.options)
            })
    }

    onMounted(() => {
        notifications ()
    })

</script>
<style scoped>
    @import "/css/DashLeftPanelLayout.css";
</style>