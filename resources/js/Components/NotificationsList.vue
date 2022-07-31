<template>
    <div class="notifications__list">
        <perfect-scrollbar>
            <div class="notifications__box" :style="[getters.notifications.length > 0 && !getters.loading ? {display: 'flex'} : {display: 'none'}]">
                <h3 class="header">Reminder Notifications</h3>
                <form :action="route('notifications.dismiss.all')" @submit.prevent="dismissAll" method="POST" enctype="multipart/form-data">
                    <button type="submit" :disabled="data.progress" @click.stop="store.commit('notificationList', true)" role="button">
                        <span v-if="!data.progress">dismiss all</span>
                        <Loading :height="32" :width="32" :color="'#0088ff'" v-if="data.progress" />
                    </button>
                </form>

                <div class="notifications__items" v-for="(notification, i) in getters.notifications" :key="i">
                    <ReminderNotifications :notification="notification" v-if="notification.notification_type === 'reminders'" />
                    <AccountNotifications :notification="notification" v-if="notification.notification_type === 'account'" />
                </div>
            </div>
            <h2 class="empty" v-if="getters.notifications.length <= 0 && !getters.loading">No new notifications!</h2>
            <Loading :height="32" :width="32" :color="'#0088ff'" v-if="getters.loading" />
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { computed, onMounted, reactive  } from 'vue'
    import { useStore } from 'vuex'
    import { useForm } from '@inertiajs/inertia-vue3'
    import moment from 'moment'
    import Loading from './loading.vue'
    import ReminderNotifications from './NotificationsList/ReminderNotifications.vue'
    import AccountNotifications from './NotificationsList/AccountNotifications.vue'

    defineProps({
        auth: Object,
    })

    const store = useStore()

    const getters = computed({
        get () {
            return {
                notifications: store.getters.notifications,
                loading: store.getters.loadingNotifications
            }
        }
    })

    const data = reactive ({
        progress: false
    })

    const dismissAll = () => {
        if (!data.progress) {
            useForm().post(route('notifications.dismiss.all'), {
                onStart: () => (data.progress = true),
                onFinish: () => {
                    data.progress = false
                },
                onSuccess: () => {
                    store.commit('dismissNotifications')
                    store.commit('getNotifyCount', 0)
                }
            })
        }
    }

    onMounted (() => {
        store.dispatch('getNotifications')
    })
</script>
<style scoped>
    @import "/css/NotificationsList.css";
</style>