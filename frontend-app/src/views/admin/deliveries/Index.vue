<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div>
      <h2 class="text-2xl font-bold">Deliveries Management</h2>
      <p class="text-gray-500 text-sm">
        Monitor all delivery processes
      </p>
    </div>

    <!-- FILTER -->
    <div>
      <select
        v-model="filterStatus"
        class="border px-3 py-2 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
      >
        <option value="">All Status</option>
        <option value="ASSIGNED">Assigned</option>
        <option value="PICKED_UP">On Delivery</option>
        <option value="DELIVERED">Delivered</option>
      </select>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr class="text-left">
            <th class="p-4">Invoice</th>
            <th class="p-4">Customer</th>
            <th class="p-4">Courier</th>
            <th class="p-4">Address</th>
            <th class="p-4">Status</th>
            <th class="p-4">Action</th>
          </tr>
        </thead>

        <tbody>

          <tr
            v-for="delivery in filteredDeliveries"
            :key="delivery.id"
            class="border-t hover:bg-gray-50 transition"
          >

            <td class="p-4 font-semibold">
              {{ delivery.invoice_number }}
            </td>

            <td class="p-4">
              {{ delivery.customer_name }}
            </td>

            <td class="p-4">
              {{ delivery.courier_name || '-' }}
            </td>

            <td class="p-4">
              {{ delivery.address }}
            </td>

            <td class="p-4">
              <span
                class="px-2 py-1 rounded text-xs"
                :class="statusColor(delivery.status)"
              >
                {{ delivery.status }}
              </span>
            </td>

            <!-- 🔥 ACTION HANYA DETAIL -->
            <td class="p-4">
              <RouterLink
                :to="`/admin/deliveries/${delivery.id}`"
                class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-blue-700 transition"
              >
                Detail
              </RouterLink>
            </td>

          </tr>

          <tr v-if="filteredDeliveries.length === 0">
            <td colspan="6"
                class="p-6 text-center text-gray-400">
              No deliveries found
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from '@/api/axios' // sesuaikan dengan config kamu

const filterStatus = ref('')
const deliveries = ref([])
const loading = ref(false)

const filteredDeliveries = computed(() => {
  if (!filterStatus.value) return deliveries.value
  return deliveries.value.filter(
    d => d.status === filterStatus.value
  )
})

function statusColor(status) {
  switch (status) {
    case 'ASSIGNED':
      return 'bg-blue-100 text-blue-700'
    case 'PICKED_UP':
      return 'bg-yellow-100 text-yellow-700'
    case 'DELIVERED':
      return 'bg-green-100 text-green-700'
    default:
      return 'bg-gray-100 text-gray-700'
  }
}

async function fetchDeliveries() {
  loading.value = true
  try {
    const res = await axios.get('/admin/deliveries')
    deliveries.value = res.data.data
  } catch (error) {
    console.error(error)
  }
  loading.value = false
}

onMounted(() => {
  fetchDeliveries()
})
</script>