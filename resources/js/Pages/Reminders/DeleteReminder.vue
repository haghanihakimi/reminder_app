<template>
    <Head title="Delete" />
    <DashboardLayout v-if="!store.getters.loggedOut">
        <SideBar/>
        <DashboardBodyLayout :auth="auth">
            <div class="dashboard__grids">
                <DashboardLeftPanel :auth="auth" v-if="!store.getters.notificationList"/>
                <NotificationsList :auth="auth" v-else/>
                <DeleteComp :auth="auth" />
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
    import DeleteComp from '../../Components/RemindersModules/DeleteComp.vue'
    import NotificationsList from '../../Components/NotificationsList.vue'

    const props = defineProps({
        auth: Object,
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
<style scoped>
    @import "/css/ReminderEditModules.css";
</style>