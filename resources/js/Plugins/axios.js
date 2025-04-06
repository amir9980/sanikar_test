import axios from 'axios'
import router from "@/router.js";
import {useAuthStore} from "@/Stores/auth.js";


function handleUnauthorized(error) {
    router.push('/auth')
    return Promise.reject(error)
}

const api = axios.create({})

api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            return handleUnauthorized(error)
        }
        return Promise.reject(error)
    }
)

export default api
