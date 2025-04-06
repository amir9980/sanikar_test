<template>
    <LoginForm v-if="form==='login'"/>
    <RegisterForm v-else-if="form==='register'"/>
</template>

<script>
import LoginForm from "@/Pages/Components/Auth/LoginForm.vue";
import {provide, ref} from "vue";
import {useAuthStore} from "@/Stores/auth.js";
import RegisterForm from "@/Pages/Components/Auth/RegisterForm.vue";
import {useRouter} from "vue-router";

export default {
    components: {LoginForm, RegisterForm},
    setup() {
        const form = ref('login');
        const authStore = useAuthStore();
        const router = useRouter();
        provide('authForm', form);

        return {form}
    }
}
</script>
