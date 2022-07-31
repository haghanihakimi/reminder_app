<template>
    <div class="navigation">
        <div class="desktop__nav">
            <ul>
                <li v-if="verified || !auth"><Link :href="route('root')" target="_self" :class="{'active': $page.component == 'Home'}">home</Link></li><li v-if="!auth">
                <Link :href="route('login')" target="_self" :class="{'active': $page.component == 'Auth/Login'}">sign in</Link></li><li v-if="!auth">
                <Link :href="route('register')" target="_self" :class="{'active': $page.component == 'Auth/Register'}">sign up</Link></li><li v-if="!auth">
                <Link href="#" target="_self" :class="{'active': $page.component == 'AboutUs'}">about us</Link></li><li v-if="!auth">
                <Link href="#" target="_self" :class="{'active': $page.component == 'ContactUs'}">contact us</Link></li><li v-if="!verified && auth">
                <form action="/" method="post" @submit.prevent="logout" enctype="multipart/form-data"><button :disable="data.progress" type="submit" role="button">sign out</button></form></li>
            </ul>
        </div>
        <div class="mobile__nav" @click.stop="toggleMnVisibility">
            <button>
                <span class="material-symbols-rounded">menu</span>
            </button>
        </div>
    </div>
</template>
<script setup>
    import { onMounted, reactive } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'

    defineProps({
        auth: Boolean,
        verified: Boolean
    })
    const store = useStore()

    const data = reactive ({
        progress: false
    })

    const toggleMnVisibility = () => {
        (store.getters.MobileNavVisibility) ? store.dispatch('toggleMobileNavVisibility', false) : 
            store.dispatch('toggleMobileNavVisibility', true)
    }

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
    
    onMounted (() => {
        window.addEventListener('click', () => {
            store.dispatch('toggleMobileNavVisibility', false)
        })
        window.addEventListener('resize', () => {
            store.dispatch('toggleMobileNavVisibility', false)
        })
    })
</script>