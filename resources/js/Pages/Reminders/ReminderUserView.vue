<template>
    <Head title="View Reminder" />
    <DashboardLayout>
        <SideBar v-if="!store.getters.loggedOut && auth.logged"/>
        <DashboardBodyLayout :auth="auth">
            <div class="dashboard__grids">
                <DashboardLeftPanel :auth="auth" v-if="!store.getters.loggedOut && auth.logged && !store.getters.notificationList"/>
                <NotificationsList :auth="auth" v-if="store.getters.notificationList && !store.getters.loggedOut && auth.logged"/>
                <ReminderUserViewComp :auth="auth" :reminder="reminder"/>
            </div>
        </DashboardBodyLayout>
    </DashboardLayout>
</template>
<script setup>
    import { computed, onMounted  } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'
    import DashboardLayout from '../../Layouts/DashboardLayout.vue'
    //Components
    import SideBar from '../../Components/Dashboard/SideBar.vue'
    import DashboardBodyLayout from '../../Layouts/DashboardBodyLayout.vue'
    import DashboardLeftPanel from '../../Components/Dashboard/DashboardLeftPanel.vue'
    import ReminderUserViewComp from '../../Components/RemindersModules/ReminderUserViewComp.vue'
    import NotificationsList from '../../Components/NotificationsList.vue'

    const props = defineProps({
        auth: Object,
        reminder: Object
    })

    const store = useStore()

    const getters = computed(() => {
        return {
        }
    })

    const notificationList = () => {
        store.commit('notificationList', false)
    }

    onMounted (() => {
        if (!store.getters.loggedOut) {
            store.dispatch('fillRemindersList')
        }

        window.addEventListener('click', () => {
            notificationList()
        })

        window.removeEventListener('click', () => {
            notificationList()
        })
    }) 
</script>
<style scoped>
    @import "/css/ReminderUserView.css";
</style>