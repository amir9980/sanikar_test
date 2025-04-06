<template>
    <form>

        <div class="p-10 md:px-20">
            <h1 class="text-xl font-bold mb-20 text-center">ثبت نام</h1>
            <div class="h-4/5 flex-col w-full flex flex-wrap justify-start">
                <div class="flex justify-center w-full mb-6">
                    <FloatLabel>
                        <InputText class="w-96" id="title" v-model="user.name"/>
                        <label for="title">نام کاربری</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-center w-full mb-6">
                    <FloatLabel>
                        <Password input-id="title" class="w-96" v-model="user.password" :feedback="true"/>
                        <label for="title">رمز عبور</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-center w-full mb-15">
                    <FloatLabel>
                        <Password input-id="title" class="w-96" v-model="user.password_confirmation" :feedback="false"/>
                        <label for="title">تایید رمز عبور</label>
                    </FloatLabel>
                </div>
                <div class="w-full flex justify-center">
                    <Button type="submit" @click.prevent="register" icon="pi pi-check" label="ثبت نام" class="w-96"/>
                </div>
                <div class="w-full flex justify-center">
                    <p class="text-sm mt-6">قبلا حساب ایجاد کردید؟&nbsp;
                        <Button @click="showLoginForm" variant="text" label="ورود" size="small"></Button>
                    </p>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
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
        const toast = useToast();
        const router = useRouter();
        const form = inject('authForm')
        const user = reactive({
            name: null,
            password: null,
            password_confirmation: null
        })

        function showLoginForm() {
            form.value = 'login';
            router.push('auth');
        }

        function register() {
            api.post('/api/v1/auth/register', {
                name: user.name,
                password: user.password,
                password_confirmation: user.password_confirmation,
            }, {
                withCredentials: true
            }).then((res) => {
                if (res.status === 200) {
                    toast.add({
                        severity: 'success',
                        summary: 'موفق',
                        detail: res.data.message,
                        life: 3000
                    });
                    form.value = 'login';
                    router.push('/auth');
                }
            }).catch(err => {
                toast.add({severity: 'error', summary: 'خطا!', detail: err.response.data.message, life: 3000});
            });
        }

        return {
            user, register, InputText, Password, showLoginForm
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
