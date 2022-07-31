import axios from 'axios'

export default {
    state: {
        remindersList: [],
        ignoredsList: [],
        remindersSearch: [],
        processingReminders: false,
        toastOptions: {
            position: "top-right",
            timeout: 7000,
            closeOnClick: false,
            pauseOnFocusLoss: true,
            pauseOnHover: true,
            draggable: false,
            draggablePercent: 0,
            showCloseButtonOnHover: false,
            hideProgressBar: true,
            closeButton: "button",
            icon: true,
            rtl: false,
            transition: "Vue-Toastification__bounce",
            maxToasts: 10,
            newestOnTop: true
        }
    },
    getters: {
        processingReminders: state => {
            return state.processingReminders
        },
        remindersList: state => {
            return state.remindersList
        },
        ignoredsList: state => {
            return state.ignoredsList
        },
        reminderForm: state => {
            return state.reminderForm
        },
        remindersSearch: state => {
            return state.remindersSearch
        },
        toastOptions: state => {
            return state.toastOptions
        }
    },
    mutations: {
        fillRemindersList (state, reminders) {
            return state.remindersList = reminders
        },
        fillIgnoredsList (state, reminders) {
            return state.ignoredsList = reminders
        },
        toggleProcessingReminders (state, status) {
            return state.processingReminders = status
        },
        exchangeLists (state, array) {
            state.remindersList.unshift(...array)
        },
        replaceRemindersList (state, newList) {
            return state.remindersList = newList
        },
        seedRemindersSearch (state, array) {
            return state.remindersSearch.unshift(...array)
        }
    },
    actions: {
        fillRemindersList ({commit}) {
            commit('toggleProcessingReminders', true)
            
            axios.get('/reminders/view/list').then(response => {
                commit('fillRemindersList', response.data.data)
            })
            .finally(() => {
                commit('toggleProcessingReminders', false)
            })
        },
        fillIgnoredsList ({commit}) {
            commit('toggleProcessingReminders', true)
            
            axios.get('/reminders/view/ignored/list').then(response => {
                commit('fillIgnoredsList', response.data.data)
            })
            .finally(() => {
                commit('toggleProcessingReminders', false)
            })
        },
        exchangeLists ({commit}, array) {
            commit('exchangeLists', array)
        },
        seedRemindersSearch ({commit}, inputs) {
            commit('toggleProcessingReminders', true)
            
            axios.get('/reminders/search/reminder', {params: {keywords: inputs}}).then(response => {
                commit('replaceRemindersList', response.data.data)
            })
            .finally(() => {
                commit('toggleProcessingReminders', false)
            })
        }
    }
}