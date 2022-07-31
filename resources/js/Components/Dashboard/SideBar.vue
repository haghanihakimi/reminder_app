<template>
    <div class="side__bar">
        <div class="side__bar__box">
            <!-- Logo Box. Left Side Box -->
            <div class="logo__box">
                <Link :href="route('root')" target="_self">
                    <span>MR</span>
                </Link>
            </div>

            <!-- Middle Box. Additional Elements Box -->
            <div class="middle__box">
                <div class="boxes profile__settings">
                    <Link :href="route('user.profile.view')" target="_self" :class="{'active': $page.component == 'Dashboard/GeneralSettings'}">
                        <span class="material-symbols-rounded">account_circle</span>
                    </Link>
                </div>
                <div class="boxes notifications">
                    <button role="button" @click.stop="store.commit('notificationList', true)" :class="{'active': $page.component == 'Dashboard/GeneralSettings'}">
                        <span class="material-symbols-rounded active" style="color: #f6993f;" v-if="getters.notifyCount > 0 && !getters.loadingNotifications">notifications_active</span>
                        <span class="material-symbols-rounded" v-if="getters.notifyCount <= 0 && !getters.loadingNotifications">notifications</span>
                        <Loading :width="24" :height="24" :color="'#e8ebf5'" v-if="getters.loadingNotifications" />
                    </button>
                </div>
                <div class="boxes create__reminder">
                    <Link :href="route('reminders.create')" target="_self" :class="{'active': $page.component == 'Dashboard/CreateReminder'}">
                        <span class="material-symbols-rounded">add</span>
                    </Link>
                </div>
                <div class="boxes extra__settings">
                    <Link :href="route('reminders.view.ignoreds')" target="_self" :class="{'active': $page.component == 'Dashboard/GeneralSettings'}">
                        <span class="material-symbols-rounded">restore_from_trash</span>
                    </Link>
                </div>
                <div class="boxes extra__settings">
                    <Link :href="route('user.settings.security.view')" target="_self" :class="{'active': $page.component == 'Dashboard/GeneralSettings'}">
                        <span class="material-symbols-rounded">settings</span>
                    </Link>
                </div>
            </div>

            <div class="signout__box">
                <form action="/" method="POST" @submit.prevent="logout" enctype="multipart/form-data">
                    <button type="submit" :disabled="data.progress" :class="data.progress ? 'inactive' : 'active'" role="button">
                        <span class="material-symbols-rounded">logout</span>
                    </button>
                </form>
            </div>
            <!-- Sign Out Box. Right Side Box -->
        </div>
    </div>
</template>
<script setup>
    import { computed, reactive  } from 'vue'
    import { useStore } from 'vuex'
    import { useForm } from '@inertiajs/inertia-vue3'
    import { Inertia } from '@inertiajs/inertia'
    import Loading from '../loading.vue'

    defineProps({
        auth: Object,
    })

    const data = reactive ({
        progress: false
    })

    const store = useStore()

    const getters = computed({
        get () {
            return {
                notifyCount: store.getters.notifyCount,
                loadingNotifications: store.getters.loadingNotifications
            }
        }
    })

    const logout = () => {
        if (!data.progress) {
            Inertia.post(route('logout'), null, {
                onStart: () => {
                    data.progress = true
                },
                onFinish: () => (data.progress = false),
                onSuccess: () => {
                    store.dispatch('toggleLoggedOut', true)
                    store.dispatch('toggleLoggedIn', false)
                }
            })
        }
    }
</script>