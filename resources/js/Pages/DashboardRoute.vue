<template>

</template>
<script>
import {useAuthStore} from "@/Stores/auth.js";
import {useRouter} from "vue-router";

export default {
    setup() {
        const authStore = useAuthStore();



        if (!authStore.user.isLoggedIn) {
            useRouter().push('/auth')
        }

    }
}
</script>
