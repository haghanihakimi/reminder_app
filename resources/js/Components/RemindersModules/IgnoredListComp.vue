<template>
    <div class="container">
        <perfect-scrollbar class="wrapper">
            <div class="inner__wrapper">
                <div class="heading">
                    <h2 class="heading__title">Ignored Reminders - Private</h2>
                    <span>All ignored reminders will be deleted forever after 7 days.</span>
                </div>
                <div class="list__box" v-if="!getters.processingReminders && getters.ignoredsList.length > 0">
                    <div class="list" v-for="(reminder, i) in getters.ignoredsList" :key="i">
                        <div class="item">
                            <span class="title">Reminder:</span>
                            <p class="reminder__title"><span class="material-symbols-rounded overdue">circle</span>&nbsp;&nbsp;{{reminder.title}}</p>
                            <p class="reminder__descriptions">{{reminder.descriptions}}</p>
                            <span class="dateTime">Due Date:</span>
                            <span class="due__date">{{moment(reminder.due).format('HH:mm DD MMMM, YYYY')}}</span>
                            <div class="controls">
                                <form action="/" method="PUT" @submit.prevent="restoreReminder(reminder.id)" enctype="multipart/form-data">
                                    <button type="submit" role="button">
                                        <span class="material-symbols-rounded restore">restore_from_trash</span>
                                        <span class="spnRestore">Restore</span>
                                    </button>
                                </form>
                                <form action="/" method="DELETE" @submit.prevent="deleteReminder(reminder.id)" enctype="multipart/form-data">
                                    <button>
                                        <span class="material-symbols-rounded delete">delete_forever</span>
                                        <span class="spnDelete">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <Loading :width="32" :height="32" :color="'#0088ff'" v-if="getters.processingReminders"/>
                <h2 class="empty__ignorelist" v-if="!getters.processingReminders && getters.ignoredsList.length <= 0">
                    No ignored reminder found...
                </h2>
            </div>
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { computed, onMounted, reactive, ref  } from 'vue'
    import { useStore } from 'vuex'
    import moment from 'moment'
    import { Inertia } from '@inertiajs/inertia'
    import Loading from '../loading.vue'

    const props = defineProps({
        auth: Object,
    })

    const store = useStore()

    const getters = computed({
        get () {
            return {
                remindersList: store.getters.remindersList,
                ignoredsList: store.getters.ignoredsList,
                processingReminders: store.getters.processingReminders
            }
        }
    })

    const data = reactive({
        progress: false,
    })

    const restoreReminder = target => {
        if (!data.progress && !getters.value.processingReminders) {
            Inertia.put(route('reminders.act.restore', {reminder: target}), {
                onStart: () => {
                    data.progress = true
                },
                onFinish: () => {
                    data.progress = false
                }
            })
            var i = getters.value.ignoredsList.filter(ignore => ignore.id === target)
            store.dispatch('exchangeLists', i)
            getters.value.ignoredsList.splice(i, 1)
        }
    }

    const deleteReminder = target => {
        if (!data.progress && !getters.value.processingReminders) {
            Inertia.delete(route('reminders.act.delete', {origin: 'ignore', reminder: target}), {
                onStart: () => {
                    data.progress = true
                },
                onFinish: () => {
                    data.progress = false
                },
            })
            var i = getters.value.ignoredsList.filter(ignore => ignore.id === target)
            getters.value.ignoredsList.splice(i, 1)
        }
    }
</script>