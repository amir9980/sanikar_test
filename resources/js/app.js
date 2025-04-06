import {createApp} from 'vue'
import router from "./router.js";
import App from "@/App.vue";
import {createPinia} from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import axios from "axios";
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import Aura from '@primeuix/themes/aura';
import Ripple from 'primevue/ripple';
import Vue3PersianDatetimePicker from 'vue3-persian-datetime-picker'
import ConfirmationService from 'primevue/confirmationservice';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;


const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);


const app = createApp(App).use(pinia).use(router).use(PrimeVue, {
    ripple: true,
    theme: {
        preset: Aura,
        options: {
            darkModeSelector: '.my-app-dark',
        }
    }
})
    .use(ToastService).directive("ripple", Ripple).use(Vue3PersianDatetimePicker, {
        name: 'date-picker',
        props: {
            format: 'YYYY-MM-DD',
            displayFormat: 'jYYYY-jMM-jDD',
            inputClass: 'date-picker',
            placeholder: 'یک تاریخ انتخاب کنید',
            color: 'rgba(29,133,114,0.84)',
            autoSubmit: true,
            clearable: true,
        }
    })
    .use(ConfirmationService);

app.mount('#app')
