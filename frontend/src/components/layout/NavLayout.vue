<script setup lang="ts">
import DashboardIcon from '@/components/icons/DashboardIcon.vue'
import SignoutIcon from '@/components/icons/SignoutIcon.vue'
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'

const { logOut } = useAuthStore()
const router = useRouter()

const signout = async () => {
  await logOut()
  await router.replace('/login')
}
</script>
<template>
  <div class="flex flex-col justify-between w-[50px] h-screen bg-[#80f5d2]">
    <div class="p-2">
      <div class="h-8 bg-white rounded text-xs flex justify-center items-center">Logo</div>
      <div class="mt-5 text-sm">
        <router-link to="/">
          <dashboard-icon class="text-slate-700 w-5 h-5 mx-auto" />
        </router-link>
      </div>
    </div>
    <div class="text-sm mb-3">
      <signout-icon @click="signout" class="text-slate-700 w-5 h-5 mx-auto cursor-pointer" />
    </div>
  </div>
</template>
