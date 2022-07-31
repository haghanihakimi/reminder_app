<template>
    <div class="forgot__password">
        <Header>
            <Logo :logo-type="'text'"/>
            <Navigation :auth="auth"/>
        </Header>
        <Sections>
            <MobileNav :auth="auth"/>
            <ForgotPasswordForm :errors="errors" :flash="flash" :status="status"/>
        </Sections>
    </div>
</template>
<script setup>
    import { computed  } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'
    import Header from '../../Layouts/HeaderLayout.vue';
    import Logo from '../../Components/HeaderComps/HeaderLogo.vue'
    import Navigation from '../../Components/HeaderComps/HeaderNavigation.vue'
    import MobileNav from '../../Components/MobileNav.vue'
    import Sections from '../../Layouts/Sections.vue'
    import ForgotPasswordForm from '../../Components/Auth/ForgotPassword/ForgotPasswordForm.vue'

    defineProps({
        auth: Boolean,
        errors: Object,
        flash: Object,
        status: Object,
    })

    const store = useStore()
</script>
<style scoped>
    @import "/css/ForgotPassowrd.css";
</style>