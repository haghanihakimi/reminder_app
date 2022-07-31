<template>
    <div class="forgot__password__container">
        <div class="forgot__password__form">
            <form action="/" method="post" @submit.prevent="sendRequest" enctype="multipart/form-data">
                <h2 class="formTitle">Please enter your email to reset your password.</h2>
                <input type="email" v-model="data.form.email" autocomplete="true" autofocus placeholder="Please enter your email">
                <button type="submit" role="button" :disabled="data.form.processing" :class="data.form.processing ? 'inactive' : 'active'">Request Link</button>
                <div v-if="status">
                    <p class="success" v-if="status.code && status.code === 200">{{status.message}}</p>
                    <p class="error" v-if="!status.code || status.code !== 200">{{status.message}}</p>
                </div>
                <div v-if="Object.keys(errors).length > 0">
                    <p class="error" v-for="(error, i) of errors" :key="i" v-html="error"></p>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import { reactive  } from 'vue'
    import { useForm } from '@inertiajs/inertia-vue3'

    defineProps({
        errors: Object,
        flash: Object,
        status: Object,
    })

    const data = reactive ({
        form: useForm({
            email: null
        }),
    })

    const sendRequest = () => {
        if (!data.form.processing) {
            data.form.post(route('password.email'), {
                onSuccess: () => {
                }
            })
        }
    }
</script>
