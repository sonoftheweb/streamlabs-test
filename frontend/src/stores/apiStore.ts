import { defineStore } from 'pinia'
import { useAuthStore } from '@/stores/authStore'
import { ref, watch } from 'vue'
import type { AxiosError, AxiosRequestConfig, AxiosResponse } from 'axios'
import axiosInstance from '@/services/axios'
import { useRouter } from 'vue-router'

export const useApiStore = defineStore('api', () => {
  const error = ref<AxiosError | null>(null)
  const loading = ref<boolean>(false)
  const authStore = useAuthStore()
  const router = useRouter()

  // Watch for token changes to adjust headers
  watch(
    () => authStore.token,
    () => {
      setAuthorizationBearer()
    }
  )

  const setAuthorizationBearer = () => {
    if (authStore.token) {
      axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${authStore.token}`
    } else {
      delete axiosInstance.defaults.headers.common['Authorization']
    }
  }

  // Log the request details, used for debug purposes
  // axiosInstance.interceptors.request.use((config) => {
  //   console.log('Route:', config.url)
  //   console.log('Request Headers:', config.headers)
  //   console.log('Authorization Header:', config.headers['Authorization'])
  //   return config
  // })

  // Loading state handler
  axiosInstance.interceptors.request.use((config) => {
    loading.value = true
    return config
  })

  // Response interceptors
  axiosInstance.interceptors.response.use(
    (response) => {
      loading.value = false
      return response
    },
    (err) => {
      loading.value = false

      if (err.response && err.response.status === 401) {
        // Clear the token from localStorage
        localStorage.removeItem('token')

        // Redirect to the login page
        router.push({ name: 'login' })
      } else {
        error.value = err
        alert(`Error making request: ${err.message}`)
      }

      return Promise.reject(err)
    }
  )

  return {
    error,
    loading,
    axiosInstance,
    setAuthorizationBearer,
    get: axiosInstance.get,
    post: axiosInstance.post,
    put: axiosInstance.put,
    delete: axiosInstance.delete,
    patch: axiosInstance.patch
  }
})
