<template>
    <div class="mobile__navigation" :class="getters.mnVisibility ? 'activeMobileNav' : 'inactiveMobileNav'">
        <nav>
            <ul>
                <li v-if="verified"><Link :href="route('root')" target="_self" :class="{'active': $page.component == 'Home'}">home</Link></li><li v-if="!auth">
                <Link :href="route('login')" target="_self" :class="{'active': $page.component == 'Auth/Login'}">sign in</Link></li><li v-if="!auth">
                <Link :href="route('register')" target="_self" :class="{'active': $page.component == 'Auth/Register'}">sign up</Link></li><li v-if="!auth">
                <Link href="#" target="_self" :class="{'active': $page.component == 'AboutUs'}">about us</Link></li><li v-if="!auth">
                <Link href="#" target="_self" :class="{'active': $page.component == 'ContactUs'}">contact us</Link></li><li v-if="!verified && auth">
                <form action="/" @submit.prevent="logout" enctype="multipart/form-data"><button :disable="data.progress" type="submit" role="button">sign out</button></form></li>
            </ul>
        </nav>
    </div>
</template>
<script setup>
    import { onMounted, reactive, computed } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'

    const props = defineProps({
        auth: Boolean,
        verified: Boolean
    })

    const store = useStore()

    const getters = computed(() => {
        return {
            mnVisibility: store.getters.MobileNavVisibility
        }
    })

    const data = reactive ({
        progress: false
    })

    const logout = () => {
        if (!data.progress) {
            Inertia.post(route('logout'), null, {
                onStart: () => {
                    data.progress = true
                    store.dispatch('toggleLoggedOut', true)
                    store.dispatch('toggleLoggedIn', false)
                },
                onFinish: () => (data.progress = false),
            })
        }
    }
</script>