<template>
    <Head title="Home" />
    <Home v-if="!store.getters.loggedIn">
        <Header>
            <Logo :logo-type="'text'"/>
            <Navigation :auth="auth"/>
        </Header>
        <Sections>
            <MobileNav :auth="auth"/>
            <Hero :auth="auth" />
        </Sections>
    </Home>
</template>
<script setup>
    import { onMounted } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia';
    import Home from '../Layouts/HomeLayout.vue'
    import Header from '../Layouts/HeaderLayout.vue';
    import Logo from '../Components/HeaderComps/HeaderLogo.vue'
    import Navigation from '../Components/HeaderComps/HeaderNavigation.vue'
    import MobileNav from '../Components/MobileNav.vue'
    import Sections from '../Layouts/Sections.vue'
    import Hero from '../Components/HomeSections/HeroSection.vue';

    defineProps({
        auth: Boolean,
        errors: Object,
        csrf: String,
        flash: Object
    })

    const store = useStore()

    onMounted(() => {
        if (store.getters.loggedIn) {
            Inertia.visit(route('dashboard'), {})
        }
    })
</script>