// src/services/AuthService.ts
import axios from 'axios'

import { ref } from 'vue'

const API_URL = 'http://localhost:8000/api'

const isAuthenticated = ref(!!localStorage.getItem('token'))

export default {
    async login(email: string, password: string) {
        try {
            const response = await axios.post(`${API_URL}/login`, { email, password })
            const token = response.data.token
            localStorage.setItem('token', token)
            isAuthenticated.value = true

            const payloadBase64 = token.split('.')[1]
            const payloadJson = atob(payloadBase64)
            const payload = JSON.parse(payloadJson)
            const roles = payload.roles || []
            localStorage.setItem('userRole', roles)

            return true
        } catch (error) {
            console.error('Erreur de connexion', error)
            return false
        }
    },

  logout() {
    localStorage.removeItem('token')
    isAuthenticated.value = false
  },

  getToken(): string | null {
    return localStorage.getItem('token')
  },

  isLoggedIn(): boolean {
    return !!localStorage.getItem('token')
  },

  getUserRole(): string | null {
    return localStorage.getItem('userRole')
  }
  
}
