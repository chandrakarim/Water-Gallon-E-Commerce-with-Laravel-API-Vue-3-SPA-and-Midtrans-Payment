<template>
<div class="space-y-8">

  <!-- HEADER -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">
        Courier Dashboard
      </h1>
      <p class="text-gray-500 text-sm">
        Ringkasan aktivitas pengiriman hari ini
      </p>
    </div>

    <RouterLink
      to="/courier/assigned-orders"
      class="bg-slate-900 text-white px-5 py-2 rounded-lg text-sm hover:bg-slate-800 transition"
    >
      View Assigned Orders
    </RouterLink>
  </div>


  <!-- STATS -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

    <!-- Assigned -->
    <div class="bg-white rounded-xl shadow p-6 border">
      <p class="text-gray-500 text-sm">Assigned Orders</p>
      <h2 class="text-3xl font-bold text-yellow-500 mt-2">
        {{ stats.assigned }}
      </h2>
    </div>

    <!-- On Delivery -->
    <div class="bg-white rounded-xl shadow p-6 border">
      <p class="text-gray-500 text-sm">On Delivery</p>
      <h2 class="text-3xl font-bold text-blue-500 mt-2">
        {{ stats.on_delivery }}
      </h2>
    </div>

    <!-- Delivered -->
    <div class="bg-white rounded-xl shadow p-6 border">
      <p class="text-gray-500 text-sm">Delivered Today</p>
      <h2 class="text-3xl font-bold text-green-500 mt-2">
        {{ stats.delivered_today }}
      </h2>
    </div>

  </div>


  <!-- RECENT ORDERS -->
  <div class="bg-white rounded-xl shadow border overflow-hidden">

    <div class="flex justify-between items-center p-5 border-b">
      <h3 class="font-semibold text-gray-700">
        Recent Orders
      </h3>

      <RouterLink
        to="/courier/assigned-orders"
        class="text-sm text-blue-600 hover:underline"
      >
        View All
      </RouterLink>
    </div>


    <table class="w-full text-sm">

  <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
    <tr>
      <th class="px-6 py-3 text-left">Invoice</th>
      <th class="px-6 py-3 text-left">Customer</th>
      <th class="px-6 py-3 text-left">Address</th>
      <th class="px-6 py-3 text-center">Items</th>
      <th class="px-6 py-3 text-right">Total</th>
      <th class="px-6 py-3 text-center">Status</th>
    </tr>
  </thead>

  <tbody class="divide-y">

    <tr v-if="loading">
      <td colspan="6" class="text-center py-6 text-gray-400">
        Loading data...
      </td>
    </tr>

    <tr
      v-for="order in recentOrders"
      :key="order.id"
      class="hover:bg-gray-50 transition"
    >

      <!-- Invoice -->
      <td class="px-6 py-4 font-medium text-gray-800">
        {{ order.invoice_number }}
      </td>

      <!-- Customer -->
      <td class="px-6 py-4">
        {{ order.user?.name }}
      </td>

      <!-- Address -->
      <td class="px-6 py-4 text-gray-500 max-w-xs break-words">
        {{ order.address?.full_address || '-' }}
      </td>

      <!-- Items -->
      <td class="px-6 py-4 text-center">
        {{ order.items?.length }}
      </td>

      <!-- Total -->
      <td class="px-6 py-4 text-right font-medium">
        Rp {{ formatPrice(order.total_price) }}
      </td>

      <!-- Status -->
      <td class="px-6 py-4 text-center">

        <span
          v-if="order.status.order === 'ASSIGNED'"
          class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700"
        >
          Assigned
        </span>

        <span
          v-else-if="order.status.order === 'PICKED_UP'"
          class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700"
        >
          On Delivery
        </span>

      </td>

    </tr>

    <tr v-if="!loading && recentOrders.length === 0">
      <td colspan="6" class="text-center py-8 text-gray-400">
        No recent orders
      </td>
    </tr>

  </tbody>

</table>

  </div>

</div>
</template>


<script setup>
import { reactive, ref, onMounted } from 'vue'
import axios from '@/api/axios'

const stats = reactive({
  assigned: 0,
  on_delivery: 0,
  delivered_today: 0
})

const recentOrders = ref([])
const loading = ref(false)


/*
|--------------------------------------------------------------------------
| FETCH DASHBOARD STATS
|--------------------------------------------------------------------------
*/

const fetchStats = async () => {
  try {

    const res = await axios.get('/courier/dashboard')

    const data = res.data.data

    stats.assigned = data.assigned
    stats.on_delivery = data.on_delivery
    stats.delivered_today = data.delivered_today

  } catch (err) {
    console.error(err)
  }
}


/*
|--------------------------------------------------------------------------
| FETCH RECENT ORDERS
|--------------------------------------------------------------------------
*/

const fetchRecentOrders = async () => {

  loading.value = true

  try {

    const res = await axios.get('/courier/orders')

    const orders = res.data.data || []

    // ambil 5 order terbaru
    recentOrders.value = orders.slice(0,5)

  } catch (err) {
    console.error(err)
  }

  loading.value = false
}


/*
|--------------------------------------------------------------------------
| FORMAT RUPIAH
|--------------------------------------------------------------------------
*/

const formatPrice = (value) => {
  return new Intl.NumberFormat('id-ID').format(value)
}


onMounted(() => {

  fetchStats()
  fetchRecentOrders()

})
</script>