<template>
    <div class="forgot__password__container">
        <div class="forgot__password__form">
            <form action="/" method="post" @submit.prevent="resetPassword" enctype="multipart/form-data">
                <h2 class="formTitle">Enter your new password</h2>
                <input type="email" v-model="form.email" autocomplete="true" autofocus placeholder="Email Address*">
                <input type="password" v-model="form.password" autocomplete="false" placeholder="Password*">
                <input type="password" v-model="form.password_confirmation" autocomplete="false" placeholder="Confirm Password*">
                <button type="submit" role="button" :disabled="data.progress" :class="data.progress ? 'inactive' : 'active'">reset password</button>
                <div v-if="status">
                    <p class="success" v-if="status.code && status.code === 200">{{status.message}}</p>
                    <p class="error" v-if="!status.code || status.code !== 200">{{status.message}}</p>
                </div>
                <div v-if="Object.keys(errors).length > 0">
                    <p class="error" v-for="(error, i) of errors" :key="i">
                        <span v-html="error"></span>
                    </p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import { computed, reactive  } from 'vue'
    import { Inertia } from '@inertiajs/inertia'
    const props = defineProps({
        email: String,
        errors: Object,
        flash: Object,
        status: Object,
        token: String,
    })

    const data = reactive ({
        progress: false
    }) 

    const form = reactive ({
        email: props.email,
        password: null,
        password_confirmation: null,
        token: props.token
    })

    const resetPassword = () => {
        if (!data.progress) {
            Inertia.post(route('password.update'), form, {
                onStart: () => (data.progress = true),
                onFinish: () => (data.progress = false),
            })
        }
    }
</script>