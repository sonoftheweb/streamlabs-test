<script setup lang="ts">
import UnauthenticatedLayout from '@/layout/UnauthenticatedLayout.vue'
import { useRoute, useRouter } from 'vue-router'
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useApiStore } from '@/stores/apiStore'

const route = useRoute()
const router = useRouter()
const api = useApiStore()
const authStore = useAuthStore()

onMounted(() => runCallback())

const runCallback = async () => {
  const response = await api.get(`/auth/${route.params.provider}/callback`, {
    params: route.query
  })
  authStore.setToken(response.data.token)
  authStore.setUser(response.data.user)
  await router.replace('/')
}
</script>
<template>
  <unauthenticated-layout>
    <div class="h-screen w-screen flex items-center justify-center">Loading ...</div>
  </unauthenticated-layout>
</template>
