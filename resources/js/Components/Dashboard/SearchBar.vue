<template>
    <div class="search__box">
        <form action="/" method="GET" enctype="multipart/form-data">
            <input type="text" v-model="data.keyword" placeholder="Search Reminders..." autofocus autocomplete="true">
            <div><span class="material-symbols-rounded">search</span></div>
        </form>
    </div>
</template>
<script setup>
    import { computed, reactive, watch  } from 'vue'
    import { useStore } from 'vuex'
    import debounce from 'lodash/debounce'
    import moment from 'moment'
    
    //Store/Vuex section starts
    const store = useStore()
    //Store/Vuex section ends

    //Props section starts
    const props = defineProps({
        auth: Object,
    })
    //Props section ends

    //Store data starts
    const getters = computed({
        get () {
            return {
                oldReminders: store.getters.remindersList
            }
        }
    })
    //Store data ends

    //Reactive data starts
    const data = reactive({
        keyword: ''
    })
    //Reactive data ends

    //Methods section starts

    const search = (param) => {
        if (param.length > 0 && param.length <= 64) {
            store.dispatch('seedRemindersSearch', param)
        } else {
            store.dispatch('fillRemindersList')
        }
    }

    //Methods section ends

    watch(() => data.keyword,
        debounce (function(values) {
            search(values)
        }, 750)
    )

</script>
<style scoped>
    @import "/css/SearchEngine.css";
</style>