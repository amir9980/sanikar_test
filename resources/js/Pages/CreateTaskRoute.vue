<template>
    <form>

        <div class="px-10 md:px-20">
            <h1 class="text-xl font-bold mb-20 text-center">ثبت وظیفه جدید</h1>
            <div class="h-4/5 flex-col w-full flex flex-wrap justify-start">
                <div class="flex justify-center w-full mb-8">
                    <FloatLabel>
                        <InputText class="w-96" id="title" v-model="data.title"/>
                        <label for="title">عنوان</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-center w-full mb-8">
                    <FloatLabel>
                    <Textarea class="w-96" id="description" v-model="data.description" rows="5" cols="30"
                              style="resize: none"/>
                        <label for="description">توضیحات</label>
                    </FloatLabel>
                </div>
                <div class="w-full grid justify-center justify-items-center">
                    <div class="w-96 mb-6 flex justify-between overflow-hidden">
                        <label for="start_date" class="">تاریخ شروع</label>
                        <date-picker id="start_date" v-model="data.start_date" format="jYYYY-jMM-jDD" simple/>
                    </div>
                    <div class="w-96 mb-15 flex justify-between overflow-hidden">
                        <label for="end_date" class="">تاریخ پایان</label>
                        <date-picker id="end_date" v-model="data.end_date" format="jYYYY-jMM-jDD" simple/>
                    </div>
                </div>
                <div class="w-full flex justify-center">
                    <Button type="submit" @click.prevent="createTask" icon="pi pi-check" raised label="ثبت" class="w-96"/>
                </div>
            </div>
        </div>
    </form>
</template>
<script setup>
import {reactive} from "vue";
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import {FloatLabel, Textarea} from "primevue";
import moment from "moment-jalaali";
import api from "../Plugins/axios.js";
import {useAuthStore} from "@/Stores/auth.js";
import {useToast} from "primevue/usetoast";
import {useRouter} from "vue-router";

const data = reactive({
    title: null,
    description: null,
    start_date: moment().format('jYYYY-jMM-jDD'),
    end_date: moment().format('jYYYY-jMM-jDD'),
});

const enteredDates = reactive({
    start_date:null,
    end_date:null,
})

const authStore = useAuthStore();
const token = authStore.user.token;
const toast = useToast();
const router = useRouter()

function createTask() {
    enteredDates.start_date = data.start_date;
    enteredDates.end_date = data.end_date;
    data.start_date = moment(enteredDates.start_date, 'jYYYY-jMM-jDD').format('YYYY-MM-DD');
    data.end_date = moment(enteredDates.end_date, 'jYYYY-jMM-jDD').format('YYYY-MM-DD');
    api.post('/api/v1/tasks', data, {
        headers: {
            'Authorization': `Bearer ${token}`
        },
    }).then(res => {
        toast.add({severity: 'success', detail: "با موفقیت ایحاد شد.", life: 3000});
        router.push('/tasks');
    }).catch(err => {
        data.start_date = enteredDates.start_date;
        data.end_date = enteredDates.end_date;
        toast.add({severity: 'error', summary: 'Error', detail: err.response.data.message, life: 3000});
    });
}

</script>

