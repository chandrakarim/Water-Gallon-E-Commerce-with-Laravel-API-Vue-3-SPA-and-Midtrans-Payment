<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div>
      <h2 class="text-2xl font-bold">Orders Management</h2>
      <p class="text-gray-500 text-sm">Monitor and manage customer orders</p>
    </div>

    <!-- FILTER + SEARCH -->
    <div class="flex flex-col md:flex-row gap-3 md:items-center md:justify-between">

      <!-- LEFT -->
      <div class="flex gap-3">

        <!-- STATUS FILTER -->
        <select
          v-model="filterStatus"
          class="border px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
          <option value="">All Status</option>
          <option value="WAITING_PAYMENT">Menunggu Pembayaran</option>
          <option value="WAITING_ASSIGN">Menunggu Kurir</option>
          <option value="ASSIGNED">Kurir Ditugaskan</option>
          <option value="PICKED_UP">Sedang Diantar</option>
          <option value="DELIVERED">Selesai</option>
          <option value="CANCELLED">Dibatalkan</option>
        </select>

      </div>

      <!-- RIGHT SEARCH -->
      <div class="w-full md:w-72">
        <input
          v-model="search"
          type="text"
          placeholder="Search invoice, status, payment..."
          class="w-full border px-4 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
      </div>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr class="text-left">
            <th class="p-4">Invoice</th>
            <th class="p-4">Status</th>
            <th class="p-4">Payment</th>
            <th class="p-4">Paid At</th>
            <th class="p-4">Action</th>
          </tr>
        </thead>

        <tbody>

          <tr
            v-for="order in filteredOrders"
            :key="order.id"
            class="border-t hover:bg-gray-50 transition"
          >
            <td class="p-4 font-semibold">
              {{ order.invoice_number }}
            </td>

            <td class="p-4">
              <span
                class="px-2 py-1 rounded text-xs capitalize"
                :class="statusColor(order.status.order)"
              >
                {{ order.status_label }}
              </span>
            </td>

            <td class="p-4">
              {{ order.payment?.method || '-' }}
            </td>

            <td class="p-4">
              {{ formatDate(order.payment?.paid_at) }}
            </td>

            <td class="p-4">
              <RouterLink
                :to="`/admin/orders/${order.id}`"
                class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-blue-700 transition"
              >
                View
              </RouterLink>
            </td>
          </tr>

          <tr v-if="filteredOrders.length === 0">
            <td colspan="5" class="p-6 text-center text-gray-400">
              No orders found
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useOrderStore } from '@/stores/order'

const filterStatus = ref('')
const search = ref('')
const orderStore = useOrderStore()

const orders = computed(() => orderStore.orders)

// 🔥 FILTER + SEARCH DIGABUNG
const filteredOrders = computed(() => {
  if (!orders.value || orders.value.length === 0) return []

  return orders.value.filter(order => {

    // filter status
    const matchStatus =
      !filterStatus.value ||
      order.status.order === filterStatus.value

    // filter search
    const keyword = search.value.toLowerCase()

    const matchSearch =
      order.invoice_number.toLowerCase().includes(keyword) ||
      order.status_label.toLowerCase().includes(keyword) ||
      (order.payment?.method || '').toLowerCase().includes(keyword)

    return matchStatus && matchSearch
  })
})

// warna status lebih lengkap
function statusColor(status) {
  switch (status?.toUpperCase()) {
    case 'WAITING_PAYMENT': return 'bg-yellow-100 text-yellow-700'
    case 'WAITING_ASSIGN': return 'bg-orange-100 text-orange-700'
    case 'ASSIGNED': return 'bg-blue-100 text-blue-700'
    case 'PICKED_UP': return 'bg-purple-100 text-purple-700'
    case 'DELIVERED': return 'bg-green-100 text-green-700'
    case 'CANCELLED': return 'bg-red-100 text-red-700'
    default: return 'bg-gray-100 text-gray-700'
  }
}

function formatDate(dateStr) {
  if (!dateStr) return '-'
  const d = new Date(dateStr)
  return d.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(async () => {
  await orderStore.fetchOrders()
})
</script>