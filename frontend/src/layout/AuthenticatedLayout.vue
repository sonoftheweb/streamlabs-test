<script setup lang="ts">
import NavLayout from '@/components/layout/NavLayout.vue'
import EventLayout from '@/components/layout/EventLayout.vue';
</script>
<template>
  <div class="flex w-full h-screen">
    <nav-layout />
    <event-layout />
    <slot />
  </div>
</template>
