import axios from 'axios'

export default {
    state: {
        notifications: [],
        notifyCount: 0,
        notificationList: false,
        loadingNotifications: false,
    },
    getters: {
        loadingNotifications: state => {
            return state.loadingNotifications
        },
        notifications: state => {
            return state.notifications
        },
        notifyCount: state => {
            return state.notifyCount
        },
        notificationList: state => {
            return state.notificationList
        }
    },
    mutations: {
        toggleLoadingNotifications (state, status) {
            return state.loadingNotifications = status
        },
        getNotifications (state, notifications) {
            return state.notifications = notifications
        },
        getNotifyCount (state, counter) {
            return state.notifyCount = counter
        },
        dismissNotifications (state) {
            return state.notifications = []
        },
        notificationList (state, status) {
            return state.notificationList = status
        }
    },
    actions: {
        getNotifications ({commit}) {
            commit ('toggleLoadingNotifications', true)
            axios.get('/notifications/catch/notification').then(response => {
                commit('getNotifications', response.data.data)
            }).finally(() => {
                commit('toggleLoadingNotifications', false)
            })
        },
        getNotifyCount ({commit}) {
            commit('toggleLoadingNotifications', true)

            axios.get('/notifications/count').then(response => {
                commit('getNotifyCount', response.data)
            }).finally(() => {
                commit('toggleLoadingNotifications', false)
            })
        }
    }
}