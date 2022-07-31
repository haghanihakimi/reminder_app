<template>
    <div class="container">
        <perfect-scrollbar class="wrapper">
            <div class="form__box" style="margin: auto;" :style="[(getters.processingReminders || getters.reminder_exists <= 0) ? {padding: '12px'} : '']">
                <div class="alert__icon" v-if="getters.reminder_exists > 0">
                    <span class="material-symbols-rounded" style="color: #0088ff">edit</span>
                </div>
                <div class="alert__title" v-if="getters.reminder_exists > 0">
                    <h3>Edit Reminder</h3>
                </div>
                <div class="box__form" v-if="getters.reminder_exists > 0">
                    <div class="wrapper" v-for="(reminder, i) in getters.reminder" :key="i">
                        <form action="/" method="PUT" @submit.prevent="saveChanges">
                            <label for="title">Reminder Title</label>
                            <input type="text" id="title" v-model="reminder.title" autocomplete="false" autofocus name="reminder_title" placeholder="Title">
                            <label for="privacy">Privacy:</label>
                            <select name="privacy" v-model="reminder.privacy" id="privacy">
                                <option value="private">private</option>
                                <option value="shared">shared</option>
                            </select>
                            <p class="privacy__note" v-if="reminder.privacy === 'shared'">
                                People with shared link can view this reminder.<br>
                                If you move reminder to trash or it gets overdue, it will be invisible.
                            </p>
                            <label for="due_date">Due Date</label>
                            <v-date-picker v-model="reminder.due" is-required mode="dateTime" is24hr>
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input
                                        id="due_date"
                                        class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                        autocomplete="false"
                                    />
                                </template>
                            </v-date-picker>
                            <label for="descriptions">Descriptions</label>
                            <textarea name="descriptions" autocomplete="false" v-model="reminder.descriptions" id="descriptions" placeholder="Reminder Descriptions" cols="30" rows="10"></textarea>
                            <button type="submit" :disabled="data.progress" v-if="!data.progress" role="button">Save Changes</button>
                            <Loading :width="32" :height="32" :color="'#0088ff'" v-if="data.progress" />
                            <p class="result__message" v-if="$page.props.status">{{$page.props.status.message}}</p>
                            <p class="result__link" v-if="$page.props.status && $page.props.status.link"><strong>Shared Link:</strong><br/>{{$page.props.status.link}}</p>
                        </form>
                    </div>
                </div>
                <Loading :width="45" :height="45" :color="'#0088ff'" v-if="getters.processingReminders" />
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
                reminder_exists: store.getters.remindersList.filter(reminder => reminder.id === localStorage.getItem('reminder')).length,
                reminder: store.getters.remindersList.filter(reminder => reminder.id === localStorage.getItem('reminder')),
                processingReminders: store.getters.processingReminders,
            }
        }
    })

    const data = reactive({
        progress: false,
        //date: moment().format('YYYY/MM/DD HH:mm'),
    })

    const formatDate = date => {
        return moment(date).format('YYYY/MM/DD HH:mm')
    }

    const saveChanges = () => {
        if (!data.progress && getters.value.reminder_exists > 0) {
            Inertia.put(route('reminders.act.edit', {reminder: getters.value.reminder[0].id}), getters.value.reminder[0], {
                onStart: () => (data.progress = true),
                onFinish: () => (data.progress = false),
                onSuccess: (response) => {
                    var i = store.getters.remindersList.filter(reminder => reminder.id === localStorage.getItem('reminder'))
                    store.getters.remindersList.splice(i, 1)
                    store.dispatch('exchangeLists', [response.props.status.reminder.data])
                }
            })
        }
    }
</script>