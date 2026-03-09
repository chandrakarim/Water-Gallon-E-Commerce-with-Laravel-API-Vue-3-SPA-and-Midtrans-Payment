<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div>
      <h2 class="text-2xl font-bold">Payments Management</h2>
      <p class="text-gray-500 text-sm">
        Monitor and verify customer payments
      </p>
    </div>

    <!-- FILTER -->
    <div>
      <select v-model="filterStatus"
              class="border px-3 py-2 rounded-lg text-sm">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="verified">Verified</option>
        <option value="rejected">Rejected</option>
      </select>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr class="text-left">
            <th class="p-4">Payment ID</th>
            <th class="p-4">Order ID</th>
            <th class="p-4">Customer</th>
            <th class="p-4">Amount</th>
            <th class="p-4">Status</th>
            <th class="p-4">Proof</th>
            <th class="p-4">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="payment in filteredPayments"
              :key="payment.id"
              class="border-t hover:bg-gray-50">

            <td class="p-4 font-semibold">
              #{{ payment.id }}
            </td>

            <td class="p-4">
              #{{ payment.order_id }}
            </td>

            <td class="p-4">
              {{ payment.customer }}
            </td>

            <td class="p-4">
              Rp {{ payment.amount }}
            </td>

            <td class="p-4">
              <span
                class="px-2 py-1 rounded text-xs capitalize"
                :class="statusColor(payment.status)"
              >
                {{ payment.status }}
              </span>
            </td>

            <td class="p-4">
              <img
                :src="payment.proof"
                class="w-16 h-16 object-cover rounded border"
              />
            </td>

            <td class="p-4 space-x-2">

              <button
                @click="updateStatus(payment, 'verified')"
                class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600"
              >
                Verify
              </button>

              <button
                @click="updateStatus(payment, 'rejected')"
                class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600"
              >
                Reject
              </button>

            </td>

          </tr>

          <tr v-if="filteredPayments.length === 0">
            <td colspan="7"
                class="p-6 text-center text-gray-400">
              No payments found
            </td>
          </tr>

        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const filterStatus = ref('')

const payments = ref([
  {
    id: 1,
    order_id: 101,
    customer: 'Budi Santoso',
    amount: 45000,
    status: 'pending',
    proof: 'https://via.placeholder.com/100'
  },
  {
    id: 2,
    order_id: 102,
    customer: 'Andi Wijaya',
    amount: 15000,
    status: 'verified',
    proof: 'https://via.placeholder.com/100'
  }
])

const filteredPayments = computed(() => {
  if (!filterStatus.value) return payments.value
  return payments.value.filter(
    p => p.status === filterStatus.value
  )
})

function statusColor(status) {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-700'
    case 'verified':
      return 'bg-green-100 text-green-700'
    case 'rejected':
      return 'bg-red-100 text-red-700'
    default:
      return 'bg-gray-100 text-gray-700'
  }
}

function updateStatus(payment, status) {
  payment.status = status
  alert('Payment updated (dummy)')
}
</script>