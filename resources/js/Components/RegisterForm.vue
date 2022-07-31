<template>
    <div class="register__container">
        <div class="register__form">
            <form @submit.prevent="CreateAccount">
                <h2 class="form__header">create new account</h2>
                <div class="inputs row">
                    <input type="text" v-model="form.first_name" autocomplete="true" autofocus name="fname" placeholder="First Name*">
                    <input type="text" v-model="form.surname" autocomplete="true" name="sname" placeholder="Surname*">
                </div>
                <div class="inputs col">
                    <input type="email" autocomplete="false" v-model="form.email" name="email" placeholder="Email*">
                    <input type="email" autocomplete="false" class="email_confirmation"  v-model="form.email_confirmation" v-if="form.email.length > 0" name="email_confirmation" placeholder="Confirm Email*">
                </div>
                <div class="inputs row">
                    <input type="password" autocomplete="false" v-model="form.password" name="password" placeholder="Password*">
                    <input type="password" autocomplete="false" v-model="form.password_confirmation" name="password_confirmation" placeholder="Confirm Password*">
                </div>
                <div class="inputs col">
                    <button type="submit" :disabled="data.progress" role="button" name="signup">Sign Up</button>
                </div>
                <div class="errors" v-if="Object.keys(errors).length > 0">
                    <ul>
                        <li v-for="(error, i) of errors" :key="i">&cross;&nbsp;{{error}}</li>
                    </ul>
                </div>
                <p class="errors" v-if="flash.messages && flash.messages !== 'An unknown error occured!'">
                    {{flash.messages}}
                </p>
            </form>
        </div>
    </div>
</template>
<script setup>
    import { useStore } from 'vuex'
    import { reactive } from 'vue'
    import { useForm } from '@inertiajs/inertia-vue3'

    const props = defineProps({
        auth: Boolean,
        errors: Object,
        flash: Object
    })

    const store = useStore()

    const data = reactive ({
        progress: false
    })

    const form = useForm ({
        first_name: '',
        surname: '',
        email: '',
        email_confirmation: '',
        password: '',
        password_confirmation: ''
    })

    const CreateAccount = () => {
        if (!data.progress) {
            form.post(route('register.create'), {
                onStart: () => (data.progress = true),
                onFinish: () => (data.progress = false),
            })
        }
    }
</script>