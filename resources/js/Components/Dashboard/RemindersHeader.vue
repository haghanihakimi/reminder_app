<template>
    <div class="reminders__header">
        <div class="boxes__item">
            <div class="counter__box">
                <span class="counter">{{(comings <= 9) ? `0${comings}` : comings}}</span>
                <span class="material-symbols-rounded" style="color: #0ea785">circle</span>
            </div>
            <div class="item__title__box">
                <span>coming events</span>
            </div>
        </div>

        <div class="boxes__item">
            <div class="counter__box">
                <span class="counter">{{(deadlines <= 9) ? `0${deadlines}` : deadlines}}</span>
                <span class="material-symbols-rounded" style="color: #f6993f">circle</span>
            </div>
            <div class="item__title__box">
                <span>Close-to-deadline</span>
            </div>
        </div>

        <div class="boxes__item">
            <div class="counter__box">
                <span class="counter">{{((overdue <= 9) ? `0${overdue}` : overdue)}}</span>
                <span class="material-symbols-rounded" style="color: #e3342f">circle</span>
            </div>
            <div class="item__title__box">
                <span>Overdue Events</span>
            </div>
        </div>
    </div>
</template>
<script setup>
    import { computed  } from 'vue'
    import { useStore } from 'vuex'
    import { Inertia } from '@inertiajs/inertia'

    defineProps({
        auth: Object,
        comings: Number,
        deadlines: Number,
        overdue: Number
    })

    const store = useStore()

    const getters = computed(() => {
        return {
        }
    })
</script>
<style scoped>
    @import "/css/RemindersHeader.css";
</style>