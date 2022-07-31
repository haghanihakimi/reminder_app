<template>
    <Head title="Verify Email" />
    <EmailVerifyNoticeLayout v-if="!store.getters.loggedOut">
        <Header>
            <Logo :logo-type="'text'"/>
            <Navigation :verified="verified" :auth="auth"/>
        </Header>
        <Sections>
            <MobileNav :verified="verified" :auth="auth"/>
            <div class="email__verification__notice">
                <div>
                    <h2>{{unverifiedMessage}}</h2>
                    <form action="/" method="POST" @submit.prevent="sendLink" enctype="multipart/form-data">
                        <button type="submit" role="button" :disabled="exhausted === 0 || data.progress" :class="(exhausted === 0) ? 'inactive' : 'active'" >Resend Verification Link [{{exhausted}}]</button>
                    </form>
                    <p v-if="flash.messages">{{flash.messages}}</p>
                </div>
            </div>
        </Sections>
    </EmailVerifyNoticeLayout>
</template>
<script setup>
    import { computed, onMounted, reactive } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia';
    import Header from '../../Layouts/HeaderLayout.vue';
    import Logo from '../../Components/HeaderComps/HeaderLogo.vue'
    import Navigation from '../../Components/HeaderComps/HeaderNavigation.vue'
    import MobileNav from '../../Components/MobileNav.vue'
    import Sections from '../../Layouts/Sections.vue'
    import EmailVerifyNoticeLayout from '../../Layouts/EmailVerifyNoticeLayout.vue'

    defineProps({
        auth: Boolean,
        errors: Object,
        flash: Object,
        verified: Boolean,
        unverifiedMessage: String,
        exhausted: Number
    })

    const store = useStore()

    const data = reactive ({
        progress: false
    })

    const getters = computed(() => {
        return {
            mnVisibility: store.getters.MobileNavVisibility
        }
    })

    const sendLink = () => {
        if (!data.progress) {
            Inertia.post(route('verification.send'), null, {
                onStart: () => (data.progress = true),
                onFinish: () => (data.progress = false),
            })
        }
    }

    onMounted (() => {
        if (store.getters.loggedOut) {
            Inertia.visit(route('login'), {})
        }
    })
</script>
<script>
export default {
    layout: EmailVerifyNoticeLayout
}
</script>