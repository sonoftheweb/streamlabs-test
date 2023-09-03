import { defineStore } from 'pinia'
import type { User } from '@/utils/types'
import { ref, watch } from 'vue'
import { useApiStore } from '@/stores/apiStore'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))
  const dataReady = ref<boolean>(false)
  const api = useApiStore()

  const loginWith = (provider: string) => {
    window.location.href = `${import.meta.env.VITE_AUTH_URL}/${provider}`
  }

  const logOut = async () => {
    await api.post('/signout')
    setToken(null)
    setUser(null)
  }

  const setToken = (t: string | null) => {
    token.value = t
    if (t !== null) {
      localStorage.setItem('token', t)
    } else {
      localStorage.removeItem('token')
    }
    api.setAuthorizationBearer()
  }

  const setUser = (u: User | null) => {
    user.value = u
  }

  const fetchUser = async () => {
    const response = await api.get(`users/current`)
    setUser(response.data)
  }

  watch([user, token], ([newUser, newToken]) => {
    dataReady.value = !!(newUser && newToken)
  })

  return { user, token, dataReady, loginWith, logOut, setToken, setUser, fetchUser }
})
