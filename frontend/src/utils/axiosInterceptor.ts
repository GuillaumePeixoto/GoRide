// utils/axiosInterceptor.ts
import axios from 'axios'
import AuthService from '@/services/AuthService'
import router from '@/router' // Importez l'objet router ici

export default function setupAxiosInterceptor() {
    axios.interceptors.response.use(
        (response) => response,
        (error) => {
            const status = error.response ? error.response.status : null
            const errorMessage = error.response ? error.response.data.message : ''

            if (status === 401 && errorMessage === 'Expired JWT Token') {
                AuthService.logout()
                router.push({ name: 'login' })
            }
            return Promise.reject(error)
        }
    )
}