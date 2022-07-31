<template>
    <Head title="Create Account" />
    <RegisterLayout v-if="!store.getters.loggedIn">
        <Header>
            <Logo :logo-type="'text'"/>
            <Navigation :auth="auth"/>
        </Header>
        <Sections>
            <MobileNav :auth="auth"/>
            <RegisterForm :auth="auth" :errors="errors" :flash="flash" />
        </Sections>
    </RegisterLayout>
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
    import RegisterLayout from '../../Layouts/RegisterLayout.vue'
    import RegisterForm from '../../Components/RegisterForm.vue'

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
    layout: RegisterLayout
}
</script>