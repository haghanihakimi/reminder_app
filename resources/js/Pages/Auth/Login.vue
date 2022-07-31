<template>
    <Head title="Login" />
    <LoginLayout v-if="!store.getters.loggedIn">
        <Header>
            <Logo :logo-type="'text'"/>
            <Navigation :auth="auth"/>
        </Header>
        <Sections>
            <MobileNav :auth="auth"/>
            <LoginForm :auth="auth" :errors="errors" :flash="flash" />
        </Sections>
    </LoginLayout>
</template>
<script setup>
    import { onMounted } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'
    import Header from '../../Layouts/HeaderLayout.vue';
    import Logo from '../../Components/HeaderComps/HeaderLogo.vue'
    import Navigation from '../../Components/HeaderComps/HeaderNavigation.vue'
    import MobileNav from '../../Components/MobileNav.vue'
    import Sections from '../../Layouts/Sections.vue'
    import LoginLayout from '../../Layouts/LoginLayout.vue'
    import LoginForm from '../../Components/LoginForm.vue'

    defineProps({
        auth: Boolean,
        errors: Object,
        flash: Object
    })

    const store = useStore()

    onMounted(() => {
        if (store.getters.loggedIn) {
            Inertia.visit(route('dashboard'), {})
        }
    })
</script>
<script>
export default {
    layout: LoginLayout
}
</script>