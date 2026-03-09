<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold">Order Detail</h2>
        <p class="text-gray-500 text-sm">Manage and view order details</p>
      </div>

      <RouterLink
        to="/admin/orders"
        class="border px-4 py-2 rounded-lg text-sm"
      >
        ← Back
      </RouterLink>
    </div>

    <!-- ORDER INFO -->
    <div class="bg-white p-6 rounded-xl shadow space-y-6">

      <div class="grid md:grid-cols-2 gap-6">

        <div>
          <p class="text-sm text-gray-500">Invoice</p>
          <p class="font-semibold">{{ order.invoice_number || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Status Label</p>
          <p class="font-semibold">{{ order.status_label || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Order Status</p>
          <p class="font-semibold">{{ order.status?.order || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Payment Status</p>
          <p class="font-semibold">{{ order.status?.payment || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Delivery Status</p>
          <p class="font-semibold">{{ order.status?.delivery || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Payment Method</p>
          <p class="font-semibold">{{ order.payment?.method || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Paid At</p>
          <p class="font-semibold">{{ formatDate(order.payment?.paid_at) }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Delivery Notes</p>
          <p class="font-semibold">{{ order.delivery?.notes || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Delivered At</p>
          <p class="font-semibold">{{ formatDate(order.delivery?.delivered_at) }}</p>
        </div>

      </div>

    </div>

    <!-- CUSTOMER INFO -->
    <div class="bg-white p-6 rounded-xl shadow space-y-4">

      <h3 class="font-semibold">Customer Info</h3>

      <div class="grid md:grid-cols-2 gap-6">

        <div>
          <p class="text-sm text-gray-500">Recipient</p>
          <p class="font-semibold">{{ order.address?.recipient_name || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Phone</p>
          <p class="font-semibold">{{ order.user?.phone || '-' }}</p>
        </div>

        <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Address</p>
          <p class="font-semibold">{{ order.address?.full_address || '-' }}</p>
        </div>

        <div class="md:col-span-2">
          <p class="text-sm text-gray-500">Notes</p>
          <p class="font-semibold">{{ order.address?.note || '-' }}</p>
        </div>

      </div>

    </div>

    <!-- COURIER INFO -->
    <div class="bg-white p-6 rounded-xl shadow space-y-4">

      <h3 class="font-semibold">Courier Info</h3>

      <div class="grid md:grid-cols-2 gap-6">

        <div>
          <p class="text-sm text-gray-500">Courier Name</p>
          <p class="font-semibold">{{ order.courier?.name || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Courier Phone</p>
          <p class="font-semibold">{{ order.courier?.phone || '-' }}</p>
        </div>

      </div>

    </div>

    <!-- ORDER ITEMS -->
    <div class="bg-white p-6 rounded-xl shadow">
      <h3 class="font-semibold mb-4">Order Items</h3>

      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr class="text-left">
            <th class="p-3">Product</th>
            <th class="p-3">Qty</th>
            <th class="p-3">Price</th>
            <th class="p-3">Subtotal</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="item in order.items || []"
            :key="item.id || item.name"
            class="border-t"
          >
            <td class="p-3">{{ item.product?.name || item.name }}</td>
            <td class="p-3">{{ item.qty }}</td>
            <td class="p-3">Rp {{ item.price }}</td>
            <td class="p-3">Rp {{ item.qty * item.price }}</td>
          </tr>

          <tr v-if="!order.items || order.items.length === 0">
            <td colspan="4" class="p-4 text-center text-gray-400">
              No items found
            </td>
          </tr>
        </tbody>
      </table>

    </div>

    <!-- TOTAL ORDER -->
    <div class="bg-white p-6 rounded-xl shadow">
      <div class="flex justify-end">
        <div class="text-right space-y-1">
          <p class="text-gray-500 text-sm">Total Order</p>
          <p class="text-xl font-bold">
            Rp {{ order.total_price || 0 }}
          </p>
        </div>
      </div>
    </div>

    <!-- ORDER HISTORY -->
    <div
      v-if="order.histories?.length"
      class="bg-white p-6 rounded-xl shadow"
    >

      <h3 class="font-semibold mb-4">Order History</h3>

      <div class="space-y-3">

        <div
          v-for="history in order.histories"
          :key="history.id"
          class="border rounded-lg p-3 flex justify-between text-sm"
        >

          <div>
            <p class="font-medium">{{ history.status_label }}</p>
            <p class="text-gray-500">{{ history.note || '-' }}</p>
          </div>

          <div class="text-gray-400 text-xs">
            {{ formatDate(history.created_at) }}
          </div>

        </div>

      </div>

    </div>

    <!-- ASSIGN KURIR -->
    <div v-if="canAssign" class="bg-white p-6 rounded-xl shadow space-y-4">

      <h3 class="font-semibold">Assign Kurir</h3>

      <select
        v-model="selectedCourier"
        class="border px-3 py-2 rounded-lg w-full text-sm"
      >
        <option disabled value="">Pilih Kurir</option>

        <option
          v-for="courier in couriers"
          :key="courier.id"
          :value="courier.id"
        >
          {{ courier.name }}
        </option>

      </select>

      <button
        @click="assignCourier"
        :disabled="!selectedCourier || assigning"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-blue-700 transition disabled:opacity-50"
      >
        {{ assigning ? 'Processing...' : 'Assign Kurir' }}
      </button>

    </div>

  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useOrderStore } from '@/stores/order'
import axios from '@/api/axios'

const route = useRoute()
const orderStore = useOrderStore()

const order = reactive({
  id: null,
  invoice_number: '',
  status: null,
  status_label: '',
  payment: null,
  delivery: null,
  items: []
})

const selectedCourier = ref('')
const couriers = ref([])
const assigning = ref(false)

const canAssign = computed(() => {
  return order.status?.order === 'WAITING_ASSIGN'
})

/* LOAD COURIERS */
async function loadCouriers() {
  try {

    const res = await axios.get('/admin/users')

    if (!res.data?.data) {
      console.warn('Response format tidak sesuai')
      return
    }

    couriers.value = res.data.data.filter(
      user => user.role === 'courier'
    )

  } catch (error) {
    console.error('Load courier error:', error)
  }
}

/* LOAD ORDER DETAIL */
onMounted(async () => {

  const id = parseInt(route.params.id)

  await orderStore.fetchOrder(id)

  Object.assign(order, orderStore.order)

  if (canAssign.value) {
    await loadCouriers()
  }

})

/* ASSIGN COURIER */
async function assignCourier() {

  if (!selectedCourier.value) return

  assigning.value = true

  try {

    const res = await axios.patch(
      `/admin/orders/${order.id}/assign`,
      {
        courier_id: selectedCourier.value
      }
    )

    Object.assign(order, res.data.data)

    alert('Kurir berhasil ditugaskan')

    selectedCourier.value = ''

  } catch (error) {

    console.error(error)

    alert('Gagal assign kurir')

  }

  assigning.value = false
}

/* FORMAT DATE */
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
</script>