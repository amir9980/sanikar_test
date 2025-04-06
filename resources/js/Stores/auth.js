import {defineStore} from "pinia";
import {reactive} from "vue";

export const useAuthStore = defineStore('auth', () => {
    const user = reactive({
        isLoggedIn: false, name: null, role: null, token: null
    });

    function login(name, role, token) {
        user.isLoggedIn = true;
        user.name = name;
        user.role = role;
        user.token = token;
    }

    function logout() {
        user.isLoggedIn = false;
        user.name = null;
        user.role = null;
        user.token = null;
    }

    return {user, login,logout}
}, {
    persist: true
})
