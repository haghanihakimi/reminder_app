<template>
    <div class="container">
        <perfect-scrollbar class="wrapper">
            <div class="form__box" :style="[(getters.processingReminders || getters.reminder_exists <= 0) ? {padding: '12px'} : '']">
                <div class="alert__icon" v-if="getters.reminder_exists > 0">
                    <span class="material-symbols-rounded" style="color:#f6993f">delete</span>
                </div>
                <div class="alert__title" v-if="getters.reminder_exists > 0">
                    <h3>Ignore Reminder</h3>
                </div>
                <div class="alert__descriptions" v-if="getters.reminder_exists > 0">
                    <p>Current Reminder will be moved to trash for 7 days and you will no longer recieve notifications about this reminder.<br/><strong>All ignored reminders will be deleted permantently after 7 days.</strong></p>
                </div>
                <div class="delete__controls" v-if="getters.reminder_exists > 0">
                    <form action="/" method="DELETE" @submit.prevent="ignoreReminder" enctype="multipart/form-data">
                        <Link :href="route('dashboard')" v-if="!data.progress" target="_self">Cancel</Link>
                        <button type="submit" v-if="!data.progress" :disabled="data.progress" style="background-color: #f6993f;" :style="[data.progress ? {opacity: 0.55} : {opacity: 1.0}]" role="button">Ignore</button>
                        <Loading :width="32" :height="32" :color="'#0088ff'" v-if="getters.processingReminders"/>
                    </form>
                </div>
                <Loading :width="32" :height="32" :color="'#0088ff'" v-if="getters.processingReminders"/>
                <h2 class="not__exsits" v-if="getters.reminder_exists <= 0 && !getters.processingReminders">
                    This reminder is permanently deleted or is moved to trash!
                </h2>
            </div>
            <div class="reminder__info" :style="getters.processingReminders ? {opacity: '0'} : {opacity: '1'}">
                <h2 v-if="getters.reminder_exists > 0">Current Reminder</h2>
                <div v-if="getters.reminder_exists > 0">
                    <span>Title:</span>
                    <p>{{getters.reminder[0].title}}</p>
                </div>
                <div v-if="getters.reminder_exists > 0">
                    <span>Due Date:</span>
                    <p>{{moment(getters.reminder[0].due).format("HH:mm - DD, MMM 'YY")}}</p>
                </div>
                <div v-if="getters.reminder_exists > 0">
                    <span>Description:</span>
                    <p>{{getters.reminder[0].descriptions}}</p>
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
    })

    const store = useStore()

    const getters = computed(() => {
        return {
            reminder_exists: store.getters.remindersList.filter(reminder => reminder.id === localStorage.getItem('reminder')).length,
            reminder: store.getters.remindersList.filter(reminder => reminder.id === localStorage.getItem('reminder')),
            processingReminders: store.getters.processingReminders
        }
    })

    const data = reactive({
        progress: false,
    })

    const ignoreReminder = () => {
        if (!data.progress && store.getters.remindersList.filter(reminder => reminder.id === localStorage.getItem('reminder')).length > 0) {
            Inertia.delete(route('reminders.act.ignore', {reminder: getters.value.reminder[0].id}), {
                onStart: () => (data.progress = true),
                onFinish: () => {
                    data.progress = false
                    localStorage.removeItem('reminder')
                },
            })
        }
    }
</script>