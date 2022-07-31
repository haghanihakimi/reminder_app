import { reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

export default {
    state: {
        MobileNavVisibility: false
    },
    getters: {
        MobileNavVisibility: state => {
            return state.MobileNavVisibility
        }
    },
    mutations: {
        toggleMobileNavVisibility (state, status) {
            return state.MobileNavVisibility = status
        }
    },
    actions: {
        toggleMobileNavVisibility ({commit}, status) {
            commit('toggleMobileNavVisibility', status)
        }
    }
}