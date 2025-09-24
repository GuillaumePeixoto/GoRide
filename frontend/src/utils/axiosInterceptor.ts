// utils/axiosInterceptor.ts
import axios from 'axios'
import AuthService from '@/services/AuthService'
import router from '@/router' // Importez l'objet router ici
import { useAuth } from '@/composables/useAuth'
const {logoutUser} = useAuth()

export default function setupAxiosInterceptor() {
    axios.interceptors.response.use(
        (response) => response,
        (error) => {
            const status = error.response ? error.response.status : null
            const errorMessage = error.response ? error.response.data.message : ''

            if (status === 401 && errorMessage === 'Expired JWT Token') {
                AuthService.logout()
                logoutUser()
                router.push({ name: 'login' })
            }
            return Promise.reject(error)
        }
    )
}