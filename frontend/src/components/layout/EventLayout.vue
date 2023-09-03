<script setup lang="ts">
import { useApiStore } from '@/stores/apiStore'
import type { UserEvent } from '@/utils/types'
import { onMounted, ref, watch } from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'

dayjs.extend(relativeTime)

const events = ref<UserEvent[]>([])
const page = ref<number>(1)
const { get, post, loading } = useApiStore()
const containerRef = ref<HTMLElement | null>(null)

onMounted(() => {
  fetchUserEvents()
  containerRef.value?.addEventListener('scroll', handleScroll)
})

watch(page, () => {
  fetchUserEvents()
})

const fetchUserEvents = () => {
  get('events', { params: { page: page.value } }).then((response) => {
    const userEvents: UserEvent[] = response.data
    if (userEvents.length) {
      events.value = [...events.value, ...userEvents]
    }
  })
}

const handleScroll = (event: Event) => {
  const el = event.target as HTMLElement
  if (el.scrollHeight - el.scrollTop === el.clientHeight) {
    page.value++
  }
}

const toggleStatus = (event: UserEvent) => {
  post(`events/${event.id}/toggle`).then((_) => {
    const index = events.value.findIndex((e) => e.id === event.id)
    if (index !== -1) {
      events.value[index].read = !events.value[index].read
    }
  })
}
</script>
<template>
  <div
    ref="containerRef"
    class="w-[350px] h-screen overflow-y-auto bg-lime-900 text-slate-200 text-sm"
  >
    <div class="px-2 pt-3 pb-1 text-lg font-bold border-b border-lime-800">Event Feed</div>
    <div class="px-2 py-3 text-xs" v-for="event in events" :key="event.id">
      {{ event.summary }}
      <div class="flex items-center">
        <span
          class="inline-flex items-center rounded-full bg-gray-100 px-1 py-0.5 text-[10px] font-medium text-gray-600 mr-1"
          >{{ dayjs(event.created_at).fromNow() }}</span
        >
        <span
          @click="toggleStatus(event)"
          v-if="event.read"
          class="cursor-pointer inline-flex items-center rounded-full bg-green-100 px-1 py-0.5 text-[10px] font-medium text-green-700"
          >Read</span
        >
        <span
          @click="toggleStatus(event)"
          v-else
          class="cursor-pointer inline-flex items-center rounded-full bg-yellow-100 px-1 py-0.5 text-[10px] font-medium text-yellow-800"
          >New</span
        >
      </div>
      <div v-if="loading" class="text-[10px] mt-2 text-center">loading ...</div>
    </div>
  </div>
</template>
