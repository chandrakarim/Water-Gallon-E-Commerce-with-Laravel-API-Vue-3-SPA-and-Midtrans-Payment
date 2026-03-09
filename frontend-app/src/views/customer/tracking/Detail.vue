<script setup>
import { ref, onMounted, computed } from "vue"
import { useRoute } from "vue-router"
import axios from "@/api/axios"

const route = useRoute()
const orderId = route.params.id

const tracking = ref([])
const loading = ref(true)

const loadTracking = async () => {
  try {

    const res = await axios.get(`/orders/${orderId}/tracking`)

    if (res.data.success) {
      tracking.value = res.data.data || []
    }

  } catch (err) {

    console.error("Tracking error", err)
    tracking.value = []

  } finally {

    loading.value = false

  }
}

onMounted(loadTracking)

const latestStatus = computed(() => {
  if (!tracking.value.length) return null
  return tracking.value[tracking.value.length - 1]
})

const iconStatus = (status) => {

  switch (status) {

    case "WAITING_PAYMENT":
      return "💳"

    case "WAITING_ASSIGN":
      return "⏳"

    case "ASSIGNED":
      return "👨‍✈️"

    case "PICKED_UP":
      return "🚚"

    case "DELIVERED":
      return "🏁"

    default:
      return "📦"
  }

}
</script>


<template>
<div class="max-w-3xl mx-auto py-10 px-6">

  <!-- TITLE -->
  <h1 class="text-2xl font-bold mb-8">
    Tracking Pesanan
  </h1>

  <!-- STATUS TERBARU -->
  <div
    v-if="latestStatus"
    class="bg-blue-50 border border-blue-200 p-5 rounded-xl mb-10"
  >
    <div class="text-sm text-gray-500 mb-1">
      Status Saat Ini
    </div>

    <div class="flex items-center gap-4">

      <div class="text-3xl">
        {{ iconStatus(latestStatus.status) }}
      </div>

      <div>

        <div class="font-semibold text-lg">
          {{ latestStatus.label }}
        </div>

        <div class="text-sm text-gray-500">
          {{ latestStatus.time }}
        </div>

      </div>

    </div>
  </div>

  <!-- LOADING -->
  <div v-if="loading" class="text-gray-500">
    Memuat tracking...
  </div>

  <!-- TIMELINE -->
  <div
    v-else
    class="relative border-l-2 border-gray-200 ml-6 pl-10 space-y-8"
  >

    <div
      v-for="(item,index) in tracking"
      :key="index"
      class="relative"
    >

      <!-- ICON -->
      <div
        class="absolute -left-[42px] flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white text-lg shadow-md"
      >
        {{ iconStatus(item.status) }}
      </div>

      <!-- CARD -->
      <div class="bg-white shadow-md rounded-xl p-5">

        <div class="font-semibold text-gray-800 text-base">
          {{ item.label }}
        </div>

        <!-- courier -->
        <div
          v-if="item.courier"
          class="text-sm text-blue-600 mt-1"
        >
          Kurir : <span class="font-medium">{{ item.courier }}</span>
        </div>

        <!-- time -->
        <div class="text-sm text-gray-500 mt-1">
          {{ item.time }}
        </div>

      </div>

    </div>

  </div>

</div>
</template>