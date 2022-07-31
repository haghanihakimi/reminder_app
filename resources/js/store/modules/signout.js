import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default {
    state: {
        loggedOut: false,
        loggedIn: false,
    },
    getters: {
        loggedOut: state => {
            return state.loggedOut
        },
        loggedIn: state => {
            return state.loggedIn
        }
    },
    mutations: {
        toggleLoggedOut (state, status) {
            return state.loggedOut = status
        },
        toggleLoggedIn (state, status) {
            return state.loggedIn = status
        }
    },
    actions: {
        toggleLoggedOut ({commit}, status) {
            commit('toggleLoggedOut', status)
        },
        toggleLoggedIn ({commit}, status) {
            commit('toggleLoggedIn', status)
        }
    }
}