<script setup lang="ts">
import UnauthenticatedLayout from '@/layout/UnauthenticatedLayout.vue'
import GoogleIcon from '@/components/icons/GoogleIcon.vue'
import TwitchIcon from '@/components/icons/TwitchIcon.vue'
import { useAuthStore } from '@/stores/authStore'

const { loginWith } = useAuthStore()
</script>

<template>
  <unauthenticated-layout>
    <div class="wide-center">
      <div class="login">
        <h3 class="text-xl font-bold">Login</h3>
        <div class="mb-5">Login with a social account</div>

        <button @click="loginWith('google')" type="button" class="login-button google">
          <GoogleIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
          Login with Google
        </button>

        <button @click="loginWith('twitch')" type="button" class="login-button twitch mt-3">
          <TwitchIcon class="-ml-0.5 h-5 w-5" aria-hidden="true" />
          Login with Twitch
        </button>
      </div>
    </div>
  </unauthenticated-layout>
</template>
