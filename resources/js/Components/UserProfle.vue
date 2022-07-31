<template>
    <div class="profile__container">
        <perfect-scrollbar class="wrapper">
            <div class="profile__header">
                <div class="profile__name">
                    <h2>{{`${data.form.first_name} ${data.form.surname}`}}</h2>
                </div>
            </div>
            <div class="profile__form">
                <div class="form__container">
                    <form action="/" method="post" @submit.prevent="saveChanges" enctype="multipart/form-data">
                        <div class="form__title">
                            <svg viewBox="0 0 456.62 488.71">
                                <path d="M216.39,467.59c-46.26-.22-87.35-9.37-127.3-23.65-19.57-7-38.49-15.77-57.71-23.71-2.51-1-4.12-2.24-4-5.56,2.13-51.79,15.05-100.16,47.41-141.58C112,225.56,161,201.24,221.64,204.6c59.84,3.32,104.7,33.32,137.25,82.75,3.71,5.63,6.62,11.79,10.22,17.5,2.15,3.42,1.54,5.54-1.28,8.34-35.93,35.72-71.67,71.62-107.6,107.33A25.17,25.17,0,0,0,252.67,434c-1.79,9.13-4.16,18.15-6.06,27.26-.71,3.39-2.25,4.87-5.81,5C231.48,466.61,222.18,467.27,216.39,467.59Z" transform="translate(-27.39 -11)"/>
                                <path d="M299.56,99a87.85,87.85,0,0,1-88.15,87.57C163,186.45,123.79,146.88,124,98.3,124.13,50.11,163.53,11,211.83,11A87.85,87.85,0,0,1,299.56,99Z" transform="translate(-27.39 -11)"/>
                                <path d="M411.51,295.59,460,344,325.11,478.86l-48.42-48.47Z" transform="translate(-27.39 -11)"/>
                                <path d="M474.16,330.37,425,281.29c12.06-13.32,35-13.24,48.69.51S487.35,318.48,474.16,330.37Z" transform="translate(-27.39 -11)"/>
                                <path d="M255.66,499.71c4-17.51,7.88-34.08,11.58-50.1l38.53,38.53Z" transform="translate(-27.39 -11)"/>
                            </svg>
                            <span>Edit Profile</span>
                        </div>
                        <div class="inputs">
                            <div class="fields">
                                <label for="first_name">First Name*</label>
                                <input type="text" name="first_name" id="first_name" v-model="data.form.first_name" :placeholder="data.form.first_name">
                            </div>
                            <div class="fields">
                                <label for="surname">Surname*</label>
                                <input type="text" name="surname" id="surname" v-model="data.form.surname" :placeholder="data.form.surname">
                            </div>
                        </div>
                        <div class="inputs">
                            <div class="fields">
                                <label for="email">Email*</label>
                                <input type="email" name="email" id="email" v-model="data.form.email">
                            </div>
                            <div class="fields" v-if="data.form.email !== props.auth.user.data.email">
                                <label for="email_confirmation">Confirm Email*</label>
                                <input type="email" name="email_confirmation" id="email_confirmation" v-model="data.form.email_confirmation">
                            </div>
                        </div>
                        <div class="inputs">
                            <div class="fields">
                                <label for="gender">Gender</label>
                                <select name="gender" v-model="data.form.gender" id="gender">
                                    <option value="null" v-if="!props.auth.user.data.gender">Choose Gender</option>
                                    <option value="female">female</option>
                                    <option value="male">male</option>
                                </select>
                            </div>
                            <div class="fields">
                                <label for="birthdate">Date of Birth</label>
                                <v-date-picker v-model="data.form.birthdate" is-required mode="date">
                                    <template v-slot="{ inputValue, inputEvents }">
                                        <input
                                            id="birthdate"
                                            class="px-2 py-1 border rounded focus:outline-none focus:border-blue-300"
                                            :value="inputValue"
                                            v-on="inputEvents"
                                            autocomplete="false"
                                        />
                                    </template>
                                </v-date-picker>
                            </div>
                        </div>
                        <div class="inputs">
                            <div class="fields">
                                <button type="submit" role="button" :disabled="!data.form.isDirty || data.form.processing" :class="data.form.isDirty || data.form.processing ? '' : 'inactive'">Save Changes</button>
                            </div>
                        </div>
                        <p class="output__message" v-if="$page.props.profile_changes_status">
                            {{ $page.props.status.message }}
                        </p>
                        <div v-if="$page.props.errors">
                            <p class="errors" v-for="(error, i) in $page.props.errors" :key="i">
                                {{ error }}
                            </p>
                        </div>
                    </form>
                    <form action="/" method="POST" @submit.prevent="sendLink" v-if="($page.props.profile_changes_status && $page.props.profile_changes_status.updated_email) || !props.auth.verified" class="verification__form" enctype="multipart/form-data">
                        <p class="changed__email__message">
                            You just changed your email address.<br/>Please verify your new email address to use your account without any limits!
                        </p>
                        <button type="submit" role="button" class="send__verification__link">Send Verification Link</button>
                    </form>
                </div>
            </div>
        </perfect-scrollbar>
    </div>
</template>
<script setup>
    import { reactive, ref } from 'vue'
    import moment from 'moment'
    import { useForm } from '@inertiajs/inertia-vue3'

    const props = defineProps({
        auth: Object,
    })
    
    const profile_change = ref(null)

    const data = reactive ({
        form: useForm({
            first_name: props.auth.user.data.first_name,
            surname: props.auth.user.data.surname,
            email: props.auth.user.data.email,
            email_confirmation: props.auth.user.data.email,
            birthdate: (props.auth.user.data.birthdate) ? moment(props.auth.user.data.birthdate).format('YYYY/MM/DD') : null,
            gender: (props.auth.user.data.gender) ? props.auth.user.data.gender : 'null',
        }),
        verificationForm: useForm()
    })

    const saveChanges = () => {
        if (!data.form.processing) {
            data.form.post(route('user.profile.save'), {
                preserveScroll: true,
                onSuccess: (response) => {
                    console.log(response)
                }
            })
        }
    }

    const sendLink = () => {
        if (!data.verificationForm.processing) {
            data.verificationForm.post(route('verification.send'), {
                onSuccess: (response) => {
                    console.log(response)
                },
            })
        }
    }
</script>
<style lang="scss" scoped>
    @import "/css/UserProfile.css";
</style>