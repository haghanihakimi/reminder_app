<template>
    <Head title="Profile" />
    <DashboardLayout v-if="!store.getters.loggedOut" :auth="auth">
        <SideBar/>
        <DashboardBodyLayout :auth="auth">
            <div class="dashboard__grids">
                <DashboardLeftPanel :auth="auth" v-if="!store.getters.notificationList"/>
                <NotificationsList :auth="auth" v-else/>
                <SecuritySettings :auth="auth" />
            </div>
        </DashboardBodyLayout>
    </DashboardLayout>
</template>
<script setup>
    import { onMounted  } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'
    import DashboardLayout from '../Layouts/DashboardLayout.vue'
    //Components
    import SideBar from '../Components/Dashboard/SideBar.vue'
    import DashboardBodyLayout from '../Layouts/DashboardBodyLayout.vue'
    import DashboardLeftPanel from '../Components/Dashboard/DashboardLeftPanel.vue'
    import NotificationsList from '../Components/NotificationsList.vue'
    import SecuritySettings from '../Components/SecuritySettingsComp.vue'

    defineProps({
        auth: Object,
    })

    const store = useStore()

    const notificationList = () => {
        store.commit('notificationList', false)
    }

    onMounted (() => {
        if (store.getters.loggedOut) {
            Inertia.visit(route('login'), {})
        } else {
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