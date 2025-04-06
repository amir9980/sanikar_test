import {createRouter, createWebHistory} from "vue-router";
import DefaultLayout from "@/Pages/Layouts/DefaultLayout.vue";
import DashboardRoute from "@/Pages/DashboardRoute.vue";
import AuthRoute from "@/Pages/AuthRoute.vue";
import ShowTasksRoute from "@/Pages/ShowTasksRoute.vue";
import CreateTaskRoute from "@/Pages/CreateTaskRoute.vue";

const routes = [{
    path: "/",
    component: DefaultLayout,
    children: [
        {path: "/dashboard", component: DashboardRoute},
        {path: "/tasks", component: ShowTasksRoute},
        {path: "/tasks/create", component: CreateTaskRoute},
    ]
},
    {path: "/auth", component: AuthRoute},
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
