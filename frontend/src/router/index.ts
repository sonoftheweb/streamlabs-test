import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: () => import('../views/HomeView.vue'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/LoginView.vue'),
      meta: {
        requiresAuth: false
      }
    },
    {
      path: '/:provider/callback',
      name: 'oauth-callback',
      component: () => import('../views/auth/OauthCallback.vue'),
      meta: {
        requiresAuth: false
      }
    }
  ]
})

router.beforeEach(async (to: RouteLocationNormalized) => {
  const userStore = useAuthStore()
  if (to.name !== 'login') {
    // case where the user has no token and no user stored in state
    if (
      Object.hasOwnProperty.call(to.meta, 'requiresAuth') &&
      to.meta.requiresAuth &&
      !userStore.token &&
      !userStore.user
    ) {
      return '/login'
    }

    // case where user has token but no user stored in state, i.e refresh
    if (
      Object.hasOwnProperty.call(to.meta, 'requiresAuth') &&
      to.meta.requiresAuth &&
      userStore.token &&
      !userStore.user
    ) {
      setTimeout(() => {
        userStore.fetchUser()
      }, 500)
    }
  }
})

export default router
