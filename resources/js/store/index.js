import { createStore } from 'vuex'

import MobileNav from './modules/menu-nav'
import SignOut from './modules/signout'
import Notifications from './modules/notifications'
import Reminders from './modules/reminders'

export default new createStore({
    modules: {
        MobileNav,
        SignOut,
        Notifications,
        Reminders
    }
})