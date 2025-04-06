<template>
    <div>
        <ConfirmDialog></ConfirmDialog>
        <Dialog v-model:visible="visibleUpdateModal" modal header="ویرایش وظیفه" class="sm:w-1/2">
            <div class="h-4/5 flex-col w-full flex flex-wrap justify-start">
                <div class="flex justify-center w-full mb-8">
                    <FloatLabel>
                        <InputText class="w-96" id="title" v-model="updatingTask.title"/>
                        <label for="title">عنوان</label>
                    </FloatLabel>
                </div>
                <div class="flex justify-center w-full mb-8">
                    <FloatLabel>
                    <Textarea class="w-96" id="description" v-model="updatingTask.description" rows="5" cols="30"
                              style="resize: none"/>
                        <label for="description">توضیحات</label>
                    </FloatLabel>
                </div>
                <div class="w-full grid justify-center justify-items-center">
                    <div class="w-96 mb-4 flex justify-between overflow-hidden">
                        <label for="start_date" class="">تاریخ شروع</label>
                        <date-picker id="start_date" v-model="updatingTask.start_date" format="jYYYY-jMM-jDD" simple/>
                    </div>
                    <div class="w-96 mb-8 flex justify-between overflow-hidden">
                        <label for="end_date" class="">تاریخ پایان</label>
                        <date-picker id="end_date" v-model="updatingTask.end_date" format="jYYYY-jMM-jDD" simple/>
                    </div>
                </div>
                <div class="w-full flex justify-center">
                    <div class="w-96 flex justify-between">
                        <label for="completed">انجام شده</label>
                        <Checkbox size="large" id="completed" v-model="updatingTask.completed"
                                  :value="updatingTask.completed" binary/>
                    </div>
                </div>
                <div class="w-full flex justify-center mt-12 mb-5">
                    <Button @click="updateTask(updatingTask.id)" icon="pi pi-check" raised label="ثبت" class="w-96"/>
                </div>
            </div>
        </Dialog>
        <DataTable size="small" stripedRows paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                   v-model:filters="filters" filterDisplay="row" :value="tasks" sortMode="multiple"
                   :globalFilterFields="['title', 'description','completed','start_date','end_date']">
            <template #header>
                <div class="flex justify-end">
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search"/>
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="جستجوی کلمات کلیدی"/>
                    </IconField>
                </div>
            </template>
            <Column field="title" sortable header="عنوان">
                <template #body="{ data }">
                    {{ data.title }}
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                               placeholder="جستجو در عنوان"/>
                </template>
            </Column>
            <Column field="description" sortable header="توضیحات">
                <template #body="{ data }">
                    {{ data.description }}
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                               placeholder="جستجو در توضیحات"/>
                </template>
            </Column>
            <Column field="completed" sortable header="وضعیت">
                <template #body="{ data }">
                    <Badge size="large" :value="data.completed" :severity=getBadgeSeverity(data.completed)></Badge>
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                               placeholder="جستجو در وضعیت"/>
                </template>
            </Column>
            <Column field="start_date" sortable header="تاریخ شروع">
                <template #body="{ data }">
                    {{ data.start_date }}
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                               placeholder="جستجو در تاریخ شروع"/>
                </template>
            </Column>
            <Column field="end_date" sortable header="تاریخ پایان">
                <template #body="{ data }">
                    {{ data.end_date }}
                </template>
                <template #filter="{ filterModel, filterCallback }">
                    <InputText v-model="filterModel.value" type="text" @input="filterCallback()"
                               placeholder="جستجو در تاریخ پایان"/>
                </template>
            </Column>
            <Column header="عملیات">
                <template #body="{ data }">
                    <Button @click="showEditModal(data)" variant="text" severity="info" size="small" label="ویرایش"
                            icon="pi pi-pencil"/>
                    <Button @click="confirmDelete(data.id)" variant="text" severity="danger" size="small" label="حذف"
                            icon="pi pi-trash"/>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
<script>
import {useRouter} from "vue-router";
import {InputText, InputIcon, IconField, FloatLabel, Textarea} from "primevue";
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import {FilterMatchMode} from '@primevue/core/api';
import api from '../Plugins/axios.js';
import {useAuthStore} from "@/Stores/auth.js";
import {reactive, ref} from "vue";
import {useToast} from "primevue/usetoast";
import ConfirmDialog from 'primevue/confirmdialog';
import {useConfirm} from "primevue/useconfirm";
import Checkbox from 'primevue/checkbox';
import Dialog from 'primevue/dialog';
import moment from "moment-jalaali";

export default {
    components: {
        Textarea,
        FloatLabel,
        DataTable,
        Column,
        InputText,
        InputIcon,
        IconField,
        Button,
        ConfirmDialog,
        Dialog,
        Checkbox,
        Badge
    },
    setup() {
        const authStore = useAuthStore();
        const tasks = ref(null);
        const visibleUpdateModal = ref(false);
        const token = authStore.user.token;
        const toast = useToast();
        const router = useRouter();
        const confirm = useConfirm();
        const filters = ref({
            global: {value: null, matchMode: FilterMatchMode.CONTAINS},
            title: {value: null, matchMode: FilterMatchMode.CONTAINS},
            description: {value: null, matchMode: FilterMatchMode.CONTAINS},
            start_date: {value: null, matchMode: FilterMatchMode.CONTAINS},
            end_date: {value: null, matchMode: FilterMatchMode.CONTAINS},
            completed: {value: null, matchMode: FilterMatchMode.CONTAINS},
        });
        const updatingTask = reactive({
            id: null,
            title: null,
            description: null,
            completed: null,
            start_date: null,
            end_date: null,
        });

        function getTasks() {
            api.get('/api/v1/tasks', {
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                withCredentials: true
            }).then(res => {
                tasks.value = res.data.tasks;
            }).catch(err => {
                toast.add({severity: 'error', summary: 'خطا', detail: err.response.data.message, life: 3000});
            })
        }

        const confirmDelete = (taskId) => {
            confirm.require({
                message: 'آیا از حذف وظیفه مطمئن هستید؟',
                header: 'تایید',
                icon: 'pi pi-exclamation-triangle',
                rejectProps: {
                    label: 'لغو',
                    severity: 'secondary',
                    outlined: true
                },
                acceptProps: {
                    label: 'حذف'
                },
                accept: () => {
                    deleteTask(taskId);
                },
                reject: () => {
                }
            });
        };


        function deleteTask(id) {
            api.delete('/api/v1/tasks/delete/' + id, {
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                withCredentials: true
            }).then(res => {
                if (res.status === 200) {
                    toast.add({severity: 'success', summary: 'موفق', detail: res.data.message, life: 3000})
                    getTasks();
                }
            }).catch(err => {
                toast.add({severity: 'error', summary: 'خطا', detail: err.response.data.message, life: 3000});
            })
        }

        function showEditModal(task) {
            updatingTask.id = task.id;
            updatingTask.title = task.title;
            updatingTask.description = task.description;
            updatingTask.completed = task.completed === 'انجام شده';
            updatingTask.start_date = task.start_date;
            updatingTask.end_date = task.end_date;
            visibleUpdateModal.value = true;
        }

        function updateTask(id) {
            api.put('/api/v1/tasks/update/' + id, {
                title: updatingTask.title,
                description: updatingTask.description,
                completed: updatingTask.completed,
                start_date: moment(updatingTask.start_date, 'jYYYY-jMM-jDD').format('YYYY-MM-DD'),
                end_date: moment(updatingTask.end_date, 'jYYYY-jMM-jDD').format('YYYY-MM-DD')
            }, {
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                withCredentials: true
            }).then(res => {
                if (res.status === 200) {
                    toast.add({severity: 'success', summary: 'موفق', detail: res.data.message, life: 3000})
                    visibleUpdateModal.value = false;
                    getTasks();
                }
            }).catch(err => {
                toast.add({severity: 'error', summary: 'خطا', detail: err.response.data.message, life: 3000});
            })
        }

        function getBadgeSeverity(status){
            return status === 'انجام شده' ? 'success' : 'warn';
        }

        if (!authStore.user.isLoggedIn) {
            router.push('/auth')
            return;
        }

        getTasks();

        return {
            tasks,
            filters,
            Button,
            ConfirmDialog,
            updateTask,
            confirmDelete,
            visibleUpdateModal,
            updatingTask,
            showEditModal,
            Checkbox,
            Badge,
            getBadgeSeverity
        }
    }
}
</script>
