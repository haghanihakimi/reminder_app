<template>
    <div class="reminders__list">
        <div class="reminders__selector">
            <form action="/" method="GET" enctype="multipart/form-data">
                <select name="categories" v-model="data.selectorType" @change="filterReminders">
                    <option value="all">All Reminders</option>
                    <option value="deadline">Close-to-deadline</option>
                    <option value="overdue">Overdue Events</option>
                </select>
            </form>
        </div>
        
        <SearchBar :auth="auth"/>
        
        <div class="reminder__list__wrapper" v-if="!store.getters.processingReminders && store.getters.remindersList.length > 0">
            <div class="reminder__list__box" v-for="(reminder, i) in filterReminders()" :key="i">
                <Link :href="route('view.user', {reminder: reminder.id})" target="_slef">
                    <div class="list__box">
                        <span class="title__box">
                            <span class="reminder__title__desc">reminder:</span>
                            <span v-if="reminder.privacy === 'private'" class="material-symbols-rounded private__icon">lock</span>
                            <span v-if="reminder.privacy === 'public'" class="material-symbols-rounded public__icon">public</span>
                            <span v-if="reminder.privacy === 'shared'" class="material-symbols-rounded shared__icon">share</span>
                        </span>
                        <span class="reminder__title"><span class="material-symbols-rounded" :class="checkReminderStatus(reminder.due)">circle</span>&nbsp;{{`${(reminder.title && reminder.title.length > 25) ? `${reminder.title.slice(0,25)}...` : reminder.title}`}}</span>
                        <span class="reminder__summary">{{`${(reminder.descriptions && reminder.descriptions.length > 32) ? `${reminder.descriptions.slice(0,32)}...` : reminder.descriptions}`}}</span>
                        <span class="reminder__time">Time:</span>
                        <span class="time">{{`${formatDates(reminder.due)}`}}</span>
                    </div>
                </Link>
                <div class="controls__box">
                    <div class="shared__link" v-if="reminder.privacy === 'shared'">
                        <p class="link" v-if="reminder.link">{{reminder.link}}</p>
                        <button @click="copyLink(reminder.link)" v-if="reminder.link"><span class="material-symbols-rounded">content_copy</span></button>
                    </div>
                    <div class="controls">
                        <Link :href="route('reminders.edit', {reminder: reminder.id})" @click="setReminderId(reminder.id)" target="_slef">
                            <span class="material-symbols-rounded">edit</span>
                            <span>edit</span>
                        </Link>
                        <Link :href="route('reminders.delete', {reminder: reminder.id})" @click="setReminderId(reminder.id)" target="_self">
                            <span class="material-symbols-rounded" style="color: #e3342f">delete</span>
                            <span style="color: #e3342f">delete</span>
                        </Link>
                        <Link :href="route('reminders.ignore', {reminder: reminder.id})" @click="setReminderId(reminder.id)" target="_self">
                            <span class="material-symbols-rounded" style="color: #f6993f">notifications_paused</span>
                            <span style="color: #f6993f">ignore</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <h2 class="reminder__notfound" v-if="store.getters.remindersList.length <= 0 && !store.getters.processingReminders">
            No scheduled remider found!
        </h2>
        <Loading :width="36" :height="36" :color="'#0088ff'" v-if="store.getters.processingReminders" />
    </div>
</template>
<script setup>
    import { computed, onMounted, reactive  } from 'vue'
    import { useStore } from 'vuex'
    import { useToast } from "vue-toastification";
    import "vue-toastification/dist/index.css";
    import moment from 'moment'
    import SearchBar from './SearchBar.vue'
    import Loading from '../loading.vue'
    
    //Store/Vuex section starts
    const store = useStore()
    //Store/Vuex section ends

    const toast = useToast()

    //Props section starts
    const props = defineProps({
        auth: Object,
    })
    //Props section ends

    //Data section starts
    const data = reactive({
        selectorType: 'all'
    })
    //Data section ends

    //Store data starts
    const getters = computed({
        get () {
            return {
                options: store.getters.toastOptions
            }
        }
    })
    //Store data ends

    //Methods section starts
    const formatDates = date => {
        return moment(date).format("HH:mm - DD, MMM 'YY") //15:10 28, Jun '22
    }

    const copyLink = link => {
        navigator.clipboard.writeText(link).then(function() {
            alert('Shared link has been copied!')
        }, function() {
            console.log('successfully failed!')
        });
    }

    const checkReminderStatus = date => {
        if (moment(date).diff(moment(), 'seconds') <= 0) {
            return 'overdue'
        }

        if (moment(date).diff(moment(), 'seconds') <= 259200 && moment(date).diff(moment(), 'seconds') >= 1) {
            return 'deadline'
        }

        if (moment(date).diff(moment(), 'seconds') > 259200) {
            return 'coming'
        }
    }

    const setReminderId = reminderId => {
        localStorage.setItem('reminder', reminderId)
    }

    const filterReminders = () => {
        if (!store.getters.processingReminders) {
            switch (data.selectorType) {
                case "deadline":
                    return store.getters.remindersList.filter(reminder => moment(reminder.due).diff(moment(), 'minutes') <= 4320 && moment(reminder.due).diff(moment(), 'minutes') >= 1)
                    break;
                case "overdue":
                    return store.getters.remindersList.filter(reminder => moment(reminder.due).diff(moment(), 'minutes') < 0)
                    break;
                default:
                    return store.getters.remindersList
                    break;
            }
        }
    }

    const watchIgnoredReminders = reminder_id => {
        let i = store.getters.remindersList.filter(reminder => reminder.id === reminder_id)
        store.getters.remindersList.splice(i, 1)
    }

    const CatchCloseDeadlineReminders = () => {
        Echo.private(`reminder.close.deadline.${props.auth.user.data.id}`)
            .listen('RemindCloseDeadline', (response) => {
                toast.warning(response.message, getters.value.options)
                store.dispatch('fillRemindersList')
                store.dispatch('getNotifyCount')
            })
    }
    const CatchDeadlineReminders = () => {
        Echo.private(`reminder.deadline.${props.auth.user.data.id}`)
            .listen('RemindDeadline', (response) => {
                store.dispatch('fillRemindersList')
                store.commit('getNotifyCount', 1)
                toast.error(response.message, getters.value.options)
            })
    }
    const CatchReminderSoftDelete = () => {
        Echo.private(`reminder.soft.delete.${props.auth.user.data.id}`)
            .listen('RemindersSoftDelete', (response) => {
                watchIgnoredReminders(response.reminder)
                store.dispatch('getNotifyCount')
            })
    }
    //Methods section ends

    onMounted (() => {
        CatchCloseDeadlineReminders()
        CatchDeadlineReminders()
        CatchReminderSoftDelete()

        store.dispatch('getNotifyCount')
    })
</script>
<style scoped>
    @import "/css/RemindersList.css";
</style>