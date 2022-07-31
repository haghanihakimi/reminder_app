<template>
    <Head title="Reset Password" />
    <div class="forgot__password">
        <Header>
            <Logo :logo-type="'text'"/>
            <Navigation :auth="auth"/>
        </Header>
        <Sections>
            <MobileNav :auth="auth"/>
            <NewPasswordForm :email="email" :errors="errors" :token="token" :flash="flash" :status="status"/>
        </Sections>
    </div>
</template>
<script setup>
    import { useStore } from 'vuex'
    import Header from '../../Layouts/HeaderLayout.vue';
    import Logo from '../../Components/HeaderComps/HeaderLogo.vue'
    import Navigation from '../../Components/HeaderComps/HeaderNavigation.vue'
    import MobileNav from '../../Components/MobileNav.vue'
    import Sections from '../../Layouts/Sections.vue'
    import NewPasswordForm from '../../Components/Auth/ForgotPassword/NewPasswordForm.vue'

    defineProps({
        auth: Boolean,
        email: String,
        errors: Object,
        flash: Object,
        status: Object,
        token: String,
    })

    const store = useStore()
</script>
<style scoped>
    @import "/css/ForgotPassowrd.css";
</style>