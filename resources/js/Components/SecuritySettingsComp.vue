<template>
    <div class="security__settings">
        <perfect-scrollbar>
            <div class="settings__header">
                <div class="settings__title">
                    <h2>Profile Settings</h2>
                </div>
            </div>
            <div class="settings__container">
                <div class="settings__formbox">

                    <div class="settings__forms settings__preferences">
                        <form :action="route('mail.notifications.toggle')" @submit.prevent="toggleMailNotifications" method="POST" enctype="multipart/form-data">
                            <h3>Preferences</h3>
                            <div class="toggle__box">
                                <span>Email Notifications</span>
                                <button class="mail__notification__toggle" type="submit" role="button">
                                    <span :class="props.auth.user.data.email_notification ? 'active' : 'inactive'">&nbsp;</span>
                                </button>
                            </div>
                        </form>
                        <p class="errors" v-if="$page.props.mail_notification_failure">
                            {{$page.props.mail_notification_failure.message}}
                        </p>
                    </div>

                    <div class="settings__forms settings__password">
                        <form action="/" method="POST" @submit.prevent="resetPasswordForm" enctype="multipart/form-data">
                            <h3>Password Reset</h3>
                            <button class="reset__password" :disabled="!props.auth.verified && data.form.processing" v-if="props.auth.verified" type="submit" role="button">Request Password Reset</button>                       
                            <span v-else style="letter-spacing: 1.5px; color: #ff6047; font-weight:600; font-size:14px">Please verify your email before your change your password!</span>
                            <button class="reset__password" type="submit" role="button" :disabled="props.auth.verified && data.form.processing" v-if="!props.auth.verified">Send Email Verification</button>
                        </form>
                        <p style="padding: 8px 0 0 0; font-size: 14px;" v-if="$page.props.status">
                            {{$page.props.status.message}}
                        </p>
                        <p style="padding: 8px 0 0 0; font-size: 14px;" v-if="$page.props.flash.messages">
                            {{$page.props.flash.messages}}
                        </p>
                    </div>

                    <div class="settings__forms account__delete">
                        <form action="/" @submit.prevent="deleteAccount" method="POST" enctype="multipart/form-data">
                            <h3>Account</h3>
                            <button type="submit" role="button">Delete Account</button>
                            <span style="font-size: 14px; opacity: 0.7;">Your account and all connected data to your account 
                            will be permanently deleted from our records after 14 days.</span>
                        </form>
                    </div>

                </div>
            </div>
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { reactive } from 'vue'
    import { useForm } from '@inertiajs/inertia-vue3'
    import { useStore } from 'vuex'

    const props = defineProps({
        auth: Object,
    })

    const store = useStore()

    const data = reactive ({
        form: useForm({
            email: props.auth.user.data.email
        })
    })
    
    const toggleMailNotifications = () => {
        useForm().post(route('mail.notifications.toggle'))
    }

    const passwordReset = () => {
        data.form.post(route('password.email'))
    }

    const emailVerification = () => {
        data.form.post(route('verification.send'))
    }

    const resetPasswordForm = () => {
        if (!data.form.processing) {
            if (props.auth.verified) {
                return passwordReset()
            } else {
                return emailVerification()
            }
        }
    }

    const deleteAccount = () => {
        if (!useForm().processing) {
            useForm().delete(route('user.account.delete'), {
                onSuccess: () => {
                    store.dispatch('toggleLoggedOut', true)
                    store.dispatch('toggleLoggedIn', false)
                }
            })
        }
    }
</script>
<style lang="scss" scoped>
    @import "/css/SecuritySettings.css";
</style>