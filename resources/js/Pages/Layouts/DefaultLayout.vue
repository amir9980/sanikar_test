<template>
    <div class="h-full">
        <div class="card" style="margin-bottom: 3rem">
            <Menubar :model="menuItems">
                <template #item="{ item, props, hasSubmenu }">
                    <router-link v-slot="{ href, navigate }" :to="item.route ?? ''"
                                 :class="[props.action.class, { 'selected': isActive(item.route) }]" custom>
                        <a v-ripple :href="href" v-bind="props.action" @click="navigate">
                            <span :class="item.icon"/>
                            <span>{{ item.label }}</span>
                        </a>
                    </router-link>
                </template>
                <template #end>
                    <div class="flex items-center gap-2">
                        <Button @click="logout" size="small" severity="danger" label="خروج"></Button>
                    </div>
                </template>
            </Menubar>
        </div>
        <RouterView></RouterView>
    </div>
</template>

<script>
import {useAuthStore} from "@/Stores/auth.js";
import Menubar from 'primevue/menubar';
import {computed, ref} from "vue";
import {useRoute, useRouter} from "vue-router";
import Button from "primevue/button";
import api from "@/Plugins/axios.js";
import {useToast} from "primevue/usetoast";

export default {
    components: {Menubar, Button},
    setup() {
        const username = useAuthStore().user.name;
        const toast = useToast();

        const menuItems = ref([
            {
                label: 'وظایف',
                icon: 'pi pi-list-check',
                items: [
                    {
                        label: 'نمایش همه',
                        icon: 'pi pi-list',
                        route: '/tasks'
                    },
                    {
                        label: 'ایجاد',
                        icon: 'pi pi-plus',
                        route: '/tasks/create'
                    }
                ]
            }]);
        const route = useRoute();
        const router = useRouter()
        const authStore = useAuthStore();
        const token = authStore.user.token;
        const isActive = (path) => computed(() => route.path === path).value;

        function logout() {
            api.post('/api/v1/auth/logout', {}, {
                headers: {
                    'Authorization': `Bearer ${token}`
                },
            }).then(res => {
                if (res.status === 200) {
                    toast.add({severity: 'success', summary: 'موفقیت آمیز', detail: res.data.message, life: 3000});
                    authStore.logout();
                    router.push('/auth');
                }
            }).catch(err => {
                toast.add({severity: 'error', summary: 'خطا', detail: err.message, life: 3000});
            });
        }

        return {username, menuItems, isActive, Button, logout}
    }
}
</script>
