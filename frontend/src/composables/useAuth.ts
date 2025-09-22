// src/composables/useAuth.ts
import axios from 'axios'
import { ref } from 'vue'

const isAuthenticated = ref(!!localStorage.getItem('token'))
const isAdmin = ref(localStorage.getItem('userRole') === 'ROLE_ADMIN')


export function useAuth() {
    const loginUser = async (email: string, password: string) => {
        try {
            const response = await axios.post('http://localhost:8000/api/login', {
                email,
                password,
            })

            const token = response.data.token
            // Stockage du token
            localStorage.setItem('token', token)

            // Met à jour l’état réactif
            isAuthenticated.value = true

            const payloadBase64 = token.split('.')[1]
            const payloadJson = atob(payloadBase64)
            const payload = JSON.parse(payloadJson)

            const roles = payload.roles || []
            const userRole = roles.includes('ROLE_ADMIN') ? 'ROLE_ADMIN' : 'ROLE_USER'

            // Stocker le rôle
            localStorage.setItem('userRole', userRole)
            isAdmin.value = userRole === 'ROLE_ADMIN'

        } catch (error) {
            isAuthenticated.value = false
            throw error
        }
    }

    const logoutUser = () => {
        localStorage.removeItem('token')
        isAuthenticated.value = false
    }

    const getToken = (): string | null => {
        return localStorage.getItem('token')
    }

    const isLoggedIn = (): boolean => {
        return !!localStorage.getItem('token')
    }

    const getUserRole = (): string | null => {
        return localStorage.getItem('userRole')
    }

    return {
        loginUser,
        logoutUser,
        isAuthenticated,
        isAdmin,
        getToken,
        isLoggedIn,
        getUserRole
    }
}
