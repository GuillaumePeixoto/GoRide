import { createRouter, createWebHistory } from 'vue-router'

// Layouts
import PublicLayout from '@/layouts/PublicLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

// Vues
import HomeView from '@/views/HomeView.vue'
import AboutView from '@/views/AboutView.vue'
import InscriptionView from '@/views/InscriptionView.vue'
import LoginView from '@/views/LoginView.vue'
import AdminDashboard from '@/views/admin/AdminDashboard.vue'
import ReservationsManagement from '@/views/admin/ReservationsManagement.vue'
import AgencesManagement from '@/views/admin/AgencesManagement.vue'
import VehiculesManagement from '@/views/admin/VehiculesManagement.vue'
import UsersManagement from '@/views/admin/UsersManagement.vue'

// Auth
import AuthService from '@/services/AuthService'

const routes = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      { path: '', name: 'home', component: HomeView },
      { path: 'about', name: 'about', component: AboutView },
      { path: 'inscription', name: 'inscription', component: InscriptionView },
      { path: 'connexion', name: 'login', component: LoginView }
    ]
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: AdminDashboard },
      { path: 'reservations', name: 'admin-reservations', component: ReservationsManagement },
      { path: 'agences', name: 'admin-agences', component: AgencesManagement },
      { path: 'vehicules', name: 'admin-vehicules', component: VehiculesManagement },
      { path: 'users', name: 'admin-users', component: UsersManagement },
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Guard d'accès
router.beforeEach((to, from, next) => {
  const isLoggedIn = AuthService.isLoggedIn()
  const isAdmin = AuthService.getUserRole() === 'ROLE_ADMIN'

  if (to.meta.requiresAuth && !isLoggedIn) {
    return next('/connexion')
  }

  if (to.meta.requiresAdmin && !isAdmin) {
    return next('/')
  }

  next()
})

export default router
