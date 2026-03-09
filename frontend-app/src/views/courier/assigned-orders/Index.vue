<template>
<div class="space-y-6">

  <!-- HEADER -->
  <div class="flex justify-between items-center">
    <h1 class="text-2xl font-semibold text-gray-800">
      Assigned Orders
    </h1>

    <div class="flex gap-3">

      <!-- SEARCH -->
      <input
        v-model="search"
        type="text"
        placeholder="Search invoice / customer..."
        class="border rounded-lg px-3 py-2 text-sm w-64"
      />

      <!-- FILTER -->
      <select
        v-model="filterStatus"
        class="border rounded-lg px-3 py-2 text-sm"
      >
        <option value="">All Status</option>
        <option value="ASSIGNED">Assigned</option>
        <option value="PICKED_UP">On Delivery</option>
      </select>

    </div>
  </div>


  <!-- TABLE -->
  <div class="bg-white shadow rounded-xl overflow-hidden border">

    <table class="w-full text-sm">

      <!-- HEADER -->
      <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
        <tr>
          <th class="px-6 py-4 text-left">Invoice</th>
          <th class="px-6 py-4 text-left">Customer</th>
          <th class="px-6 py-4 text-left">Address</th>
          <th class="px-6 py-4 text-center">Items</th>
          <th class="px-6 py-4 text-right">Total</th>
          <th class="px-6 py-4 text-center">Status</th>
          <th class="px-6 py-4 text-center">Action</th>
        </tr>
      </thead>


      <!-- BODY -->
      <tbody class="divide-y">

        <!-- LOADING -->
        <tr v-if="loading">
          <td colspan="7" class="text-center py-6 text-gray-400">
            Loading orders...
          </td>
        </tr>


        <!-- DATA -->
        <tr
          v-for="order in filteredOrders"
          :key="order.id"
          class="hover:bg-gray-50 transition"
        >

          <!-- INVOICE -->
          <td class="px-6 py-4 font-medium text-gray-800">
            {{ order.invoice_number }}
          </td>


          <!-- CUSTOMER -->
          <td class="px-6 py-4">
            {{ order.user?.name || 'Customer' }}
          </td>


          <!-- ADDRESS -->
          <td class="px-6 py-4 text-gray-500 leading-relaxed">

            <div v-if="order.address">

              <div>
                {{ order.address.street }}
              </div>

              <div class="text-xs text-gray-400">
                {{ order.address.district }},
                {{ order.address.city }}
              </div>

              <div class="text-xs text-gray-400">
                {{ order.address.province }}
                {{ order.address.postal_code }}
              </div>

            </div>

            <div v-else class="text-gray-400">
              No address
            </div>

          </td>


          <!-- ITEMS -->
          <td class="px-6 py-4 text-center">
            {{ order.items?.length || 0 }}
          </td>


          <!-- TOTAL -->
          <td class="px-6 py-4 text-right font-medium">
            Rp {{ formatPrice(order.total_price) }}
          </td>


          <!-- STATUS -->
          <td class="px-6 py-4 text-center">

            <span
              v-if="order.status.order === 'ASSIGNED'"
              class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700"
            >
              Kurir Ditugaskan
            </span>

            <span
              v-if="order.status.order === 'PICKED_UP'"
              class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700"
            >
              Sedang Diantar
            </span>

          </td>


          <!-- ACTION -->
          <td class="px-6 py-4 text-center">

            <RouterLink
              :to="`/courier/orders/${order.id}`"
              class="bg-slate-900 hover:bg-slate-800 text-white text-xs px-4 py-2 rounded-lg"
            >
              Detail
            </RouterLink>

          </td>

        </tr>


        <!-- EMPTY -->
        <tr v-if="!loading && filteredOrders.length === 0">
          <td colspan="7" class="text-center py-8 text-gray-400">
            No orders found
          </td>
        </tr>

      </tbody>

    </table>

  </div>

</div>
</template>



<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from '@/api/axios'

const orders = ref([])
const loading = ref(false)

const search = ref('')
const filterStatus = ref('')


/*
|--------------------------------------------------------------------------
| FETCH ORDERS
|--------------------------------------------------------------------------
*/

const fetchOrders = async () => {

  loading.value = true

  try {

    const res = await axios.get('/courier/orders')

    orders.value = res.data.data || res.data

  } catch (err) {

    console.error(err)

  }

  loading.value = false

}


/*
|--------------------------------------------------------------------------
| SEARCH + FILTER
|--------------------------------------------------------------------------
*/

const filteredOrders = computed(() => {

  let data = orders.value

  if (filterStatus.value) {

    data = data.filter(o =>
      o.status?.order === filterStatus.value
    )

  }

  if (search.value) {

    const s = search.value.toLowerCase()

    data = data.filter(o =>
      o.invoice_number?.toLowerCase().includes(s) ||
      o.user?.name?.toLowerCase().includes(s)
    )

  }

  return data

})


/*
|--------------------------------------------------------------------------
| FORMAT RUPIAH
|--------------------------------------------------------------------------
*/

const formatPrice = (value) => {
  return new Intl.NumberFormat('id-ID').format(value)
}


onMounted(fetchOrders)

</script>