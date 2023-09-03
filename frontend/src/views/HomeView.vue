<script setup lang="ts">
import AuthenticatedLayout from '@/layout/AuthenticatedLayout.vue'
import { useApiStore } from '@/stores/apiStore'
import { onMounted, ref } from 'vue'
import { useHelper } from '@/composables/helpers'

const { get } = useApiStore()
const { formatCurrency, formatNumber } = useHelper()

const loading = ref<boolean>(false)
const donationsCount = ref<number>(0)
const merchCount = ref<number>(0)
const topSalesItems = ref<string[]>([])
const followersCount = ref<number>(0)

const fetchRevenues = async () => {
  loading.value = true
  const revenueRequests = [
    get('/users/current/revenue/donations'),
    get('/users/current/revenue/merch'),
    get('/users/current/revenue/merch/top'),
    get('/users/current/followers/count')
  ]

  const [donations, merch, topSales, followers] = await Promise.all(revenueRequests)
  donationsCount.value = donations.data
  merchCount.value = merch.data
  topSalesItems.value = topSales.data
  followersCount.value = followers.data
  loading.value = false
}

onMounted(() => fetchRevenues())
</script>

<template>
  <authenticated-layout>
    <div v-if="!loading" class="mx-auto w-[70%] mt-10">
      <div class="grid grid-cols-1 items-center gap-px bg-white/5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8 rounded-tl rounded-bl">
          <p class="text-sm font-medium leading-6 text-gray-400">Total Donations</p>
          <p class="mt-2 flex flex-col items-baseline gap-x-2">
            <span class="text-4xl font-semibold tracking-tight text-white">{{
              formatCurrency(donationsCount)
            }}</span>
            <span class="text-sm text-gray-400">in past 30 days</span>
          </p>
        </div>
        <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
          <p class="text-sm font-medium leading-6 text-gray-400">Total merch sale</p>
          <p class="mt-2 flex flex-col items-baseline gap-x-2">
            <span class="text-4xl font-semibold tracking-tight text-white">{{ formatCurrency(merchCount) }}</span>
            <span class="text-sm text-gray-400">in past 30 days</span>
          </p>
        </div>
        <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8">
          <p class="text-sm font-medium leading-6 text-gray-400">Followers</p>
          <p class="mt-2 flex flex-col items-baseline gap-x-2">
            <span class="text-4xl font-semibold tracking-tight text-white">{{
              formatNumber(followersCount)
            }}</span>
            <span class="text-sm text-gray-400">new followers</span>
          </p>
        </div>
        <div class="bg-gray-900 px-4 py-6 sm:px-6 lg:px-8 rounded-tr rounded-br">
          <p class="text-sm font-medium leading-6 text-gray-400">Top merchs</p>
          <p class="mt-2 flex flex-col items-baseline gap-x-2">
            <div class="py-2">
              <div
              v-for="(item, i) in topSalesItems"
              :key="i"
              class="inline-flex items-center mr-3 rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10"
              >{{ item }}</div
            >
            </div>
            <span class="text-sm text-gray-400">in past 30 days</span>
          </p>
        </div>
      </div>
    </div>
  </authenticated-layout>
</template>
