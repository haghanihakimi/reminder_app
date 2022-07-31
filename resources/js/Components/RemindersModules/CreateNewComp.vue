<template>
    <div class="container">
        <perfect-scrollbar class="wrapper">
            <div class="form__box" style="margin: auto;" :style="[(getters.processingReminders) ? {padding: '12px'} : '']">
                <div class="alert__icon">
                    <span class="material-symbols-rounded" style="color: #0088ff">add_circle</span>
                </div>
                <div class="alert__title">
                    <h3>Create Reminder</h3>
                </div>
                <div class="box__form" v-if="!getters.processingReminders">
                    <div class="wrapper">
                        <form action="/" method="PUT" @submit.prevent="NewReminder">
                            <label for="title">Reminder Title</label>
                            <input type="text" id="title" v-model="data.newForm.title" autocomplete="false" autofocus name="reminder_title" placeholder="Title">
                            <label for="privacy">Privacy:</label>
                            <select name="privacy" v-model="data.newForm.privacy" id="privacy">
                                <option value="private">private</option>
                                <option value="shared">shared</option>
                                <option value="public">public</option>
                            </select>
                            <p class="privacy__note" v-if="data.newForm.privacy === 'shared'">
                                People with shared link can view this reminder.<br>
                                If you move reminder to trash or it gets overdue, it will be invisible.
                            </p>
                            <label for="due_date">Due Date</label>
                            <v-date-picker v-model="data.newForm.due" is-required mode="dateTime" is24hr>
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
                            <textarea name="descriptions" autocomplete="false" v-model="data.newForm.descriptions" id="descriptions" placeholder="Reminder Descriptions" cols="30" rows="10"></textarea>
                            <button type="submit" :disabled="data.newForm.processing" v-if="!data.newForm.processing" role="button">Create New Rminder</button>
                            <Loading :width="32" :height="32" :color="'#0088ff'" v-if="data.newForm.processing" />
                        </form>
                    </div>
                </div>
                <Loading :width="32" :height="32" :color="'#0088ff'" v-if="getters.processingReminders" />
            </div>
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { computed, reactive  } from 'vue'
    import { useStore } from 'vuex'
    import moment from 'moment'
    import { useForm } from '@inertiajs/inertia-vue3'
    import Loading from '../loading.vue'

    const props = defineProps({
        auth: Object,
    })

    const store = useStore()

    const getters = computed({
        get () {
            return {
                processingReminders: store.getters.processingReminders
            }
        }
    })

    const data = reactive({
        newForm: useForm ({
            title: '',
            privacy: 'private',
            due: moment().format('YYYY/MM/DD HH:mm'),
            descriptions: ''
        })
    })

    const formatDate = date => {
        return moment(date).format('YYYY/MM/DD HH:mm')
    }

    const NewReminder = () => {
        data.newForm.post(route('reminders.act.create'), {
            preserveScroll: true,
            onSuccess: (response) => {
                store.dispatch('exchangeLists', [response.props.status.reminder.data])
                data.newForm.reset()
            }
        })
    }
</script>