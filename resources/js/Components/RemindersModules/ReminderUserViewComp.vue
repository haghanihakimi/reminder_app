<template>
    <div class="container">
        <perfect-scrollbar class="wrapper">
            <div class="inner__wrapper">
                <div class="list__box">
                    <div class="list">
                        <div class="item">
                            <span class="title">Reminder:</span>
                            <p class="reminder__title"><span class="material-symbols-rounded overdue" :class="checkReminderStatus">circle</span>&nbsp;&nbsp;{{reminder.data.title}}</p>
                            <p class="reminder__descriptions">{{reminder.data.descriptions}}</p>
                            <span class="dateTime">Due Date:</span>
                            <span class="due__date">{{moment(reminder.data.due).format('HH:mm DD MMMM, YYYY')}}</span>
                            <div class="controls" v-if="!store.getters.loggedOut && auth.logged && auth.canEdit">
                                <Link :href="route('reminders.edit', {reminder: reminder.data.id})" target="_self">
                                    <span class="material-symbols-rounded edit">edit</span>
                                    <span class="edit">Edit</span>
                                </Link>
                                <Link :href="route('reminders.delete', {reminder: reminder.data.id})" target="_self">
                                    <span class="material-symbols-rounded delete">delete</span>
                                    <span class="delete">Delete</span>
                                </Link>
                                <Link :href="route('reminders.ignore', {reminder: reminder.data.id})" target="_self">
                                    <span class="material-symbols-rounded ignore">notifications_paused</span>
                                    <span class="ignore">Ignore</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { computed, onMounted, reactive  } from 'vue'
    import { useStore } from 'vuex'
    import moment from 'moment'
    import { Inertia } from '@inertiajs/inertia'
    import Loading from '../loading.vue'

    const props = defineProps({
        auth: Object,
        reminder: Object
    })

    const store = useStore()

    const checkReminderStatus = computed(() => {
        let status = 'overdue'

        if (moment(props.reminder.data.due).diff(moment(), 'minutes') < 0) {
            status = 'overdue'
        }

        if (moment(props.reminder.data.due).diff(moment(), 'minutes') <= 4320 && moment(props.reminder.data.due).diff(moment(), 'minutes') >= 1) {
            status = 'deadline'
        }

        if (moment(props.reminder.data.due).diff(moment(), 'minutes') > 4320) {
            status = 'coming'
        }

        return status
    })

    const data = reactive({
        progress: false,
        //date: moment().format('YYYY/MM/DD HH:mm'),
    })

    const formatDate = date => {
        return moment(date).format('YYYY/MM/DD HH:mm')
    }
</script>