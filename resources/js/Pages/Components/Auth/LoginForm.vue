<template>
    <form>
        <div class="p-10 md:px-20">
            <h1 class="text-xl font-bold mb-20 text-center">ورود</h1>
            <div class="h-4/5 flex-col w-full flex flex-wrap justify-start">
                <div class="flex justify-center w-full mb-5">
                    <FloatLabel>
                        <InputText class="w-96" id="title" v-model="user.name"/>
                        <label for="title">نام کاربری</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-center w-full mb-15">
                    <FloatLabel>
                        <Password input-id="title" class="w-96" v-model="user.password" :feedback="false"/>
                        <label for="title">رمز عبور</label>
                    </FloatLabel>
                </div>
                <div class="w-full flex justify-center">
                    <Button type="submit" @click.prevent="login" icon="pi pi-check" label="ورود" class="w-96"/>
                </div>
                <div class="w-full flex justify-center">
                    <p class="text-sm mt-6">حساب کاربری ندارید؟&nbsp;
                        <Button @click="showRegisterForm" variant="text" label="ثبت نام" size="small"></Button>
                    </p>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import {useAuthStore} from "@/Stores/auth.js";
import api from "../../../Plugins/axios.js";
import {FloatLabel, InputText} from "primevue";
import {inject, reactive} from "vue";
import {useRouter} from "vue-router";
import {useToast} from "primevue/usetoast";
import Button from "primevue/button";
import Password from 'primevue/password';

export default {
    components: {FloatLabel, Button, InputText, Password},
    setup() {
        const authStore = useAuthStore();
        const form = inject('authForm');
        const toast = useToast();
        const router = useRouter();
        const user = reactive({
            name: null,
            password: null
        })

        function login() {
            api.get('/sanctum/csrf-cookie').then(response => {
                api.post('/api/v1/auth/login', {
                    name: user.name,
                    password: user.password
                }, {
                    withCredentials: true
                }).then((res) => {
                    if (res.status === 200) {
                        const name = res.data.name;
                        const role = res.data.role;
                        const token = res.data.token;
                        authStore.login(name, role, token);
                        toast.add({
                            severity: 'success',
                            summary: 'خوش آمدید',
                            detail: "با موفقیت وارد شدید.",
                            life: 3000
                        });
                        router.push('/dashboard');
                    }
                }).catch(err => {
                    toast.add({severity: 'error', summary: 'Error', detail: err.response.data.message, life: 3000});
                });
            })
        }

        function showRegisterForm() {
            form.value = 'register';
        }

        return {
            user, login, InputText, Password, showRegisterForm
        }
    }

}

</script>

<style lang="scss">
.p-password {
    input {
        width: 100% !important;
    }
}
</style>
