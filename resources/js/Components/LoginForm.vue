<template>
    <div class="login__container">
        <form @submit.prevent="loginUser">
            <h2 class="header"><span>sign in</span> to set <span>reminders</span>!</h2>
            <div class="inputs">
                <!-- <p class="login__errors" v-if="flash.messages">
                    {{ flash.messages }}
                </p> -->
                <input type="text" name="email" v-model="loginForm.email" autocomplete="false" autofocus placeholder="Email/Username">
                <input type="password" name="password" v-model="loginForm.password" autocomplete="false" placeholder="Passowrd">
                <button type="submit" :disabled="loginForm.processing" name="login">sign in</button>
                <Link :href="route('password.request')">forgotten account</Link>
                <div v-if="errors">
                    <p class="login__errors" v-for="(error, i) in errors" :key="i">
                        {{ error }}
                    </p>
                </div>
                <div v-if="$page.props.status">
                    <p class="login__errors">
                        {{ $page.props.status }}
                    </p>
                </div>
            </div>
            <div class="title">
                <span>Or login with</span>
            </div>
        </form>
        
        <oAuthLogins />
    </div>
</template>
<script setup>
    import { reactive } from 'vue'
    import { useStore } from 'vuex'
    import { useForm } from '@inertiajs/inertia-vue3'
    import oAuthLogins from './oAuthLogins.vue'

    const props = defineProps({
        auth: Boolean,
        errors: Object,
        flash: Object
    })

    const store = useStore()

    const loginForm = useForm ({
        email: '',
        password: ''
    })

    const loginUser = () => {
        if (!loginForm.processing) {
            loginForm.post(route('login.logging'), {
                onSuccess: () => {
                    store.dispatch('toggleLoggedOut', false)
                    store.dispatch('toggleLoggedIn', true)
                }
            })
        }
    }
</script>
