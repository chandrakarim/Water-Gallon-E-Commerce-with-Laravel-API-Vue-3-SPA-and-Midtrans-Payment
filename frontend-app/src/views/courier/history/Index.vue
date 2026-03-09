<template>
<div class="space-y-6">

  <!-- HEADER -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-2xl font-semibold text-gray-800">
        Delivery History
      </h1>
      <p class="text-sm text-gray-500">
        Riwayat pengiriman yang telah selesai
      </p>
    </div>
  </div>



  <!-- TABLE -->
  <div class="bg-white rounded-xl shadow border overflow-hidden">

    <table class="w-full text-sm">

      <!-- HEAD -->
      <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
        <tr>
          <th class="px-6 py-4 text-left">Invoice</th>
          <th class="px-6 py-4 text-left">Customer</th>
          <th class="px-6 py-4 text-left">Address</th>
          <th class="px-6 py-4 text-center">Items</th>
          <th class="px-6 py-4 text-right">Total</th>
          <th class="px-6 py-4 text-center">Delivered At</th>
          <th class="px-6 py-4 text-center">Status</th>
          <th class="px-6 py-4 text-center">Detail</th>
        </tr>
      </thead>



      <!-- BODY -->
      <tbody class="divide-y">

        <!-- LOADING -->
        <tr v-if="loading">
          <td colspan="8" class="text-center py-6 text-gray-400">
            Loading history...
          </td>
        </tr>



        <!-- DATA -->
        <tr
          v-for="order in orders"
          :key="order.id"
          class="hover:bg-gray-50 transition"
        >

          <!-- INVOICE -->
          <td class="px-6 py-4 font-medium text-gray-800">
            {{ order.invoice_number }}
          </td>


          <!-- CUSTOMER -->
          <td class="px-6 py-4">
            {{ order.user?.name }}
          </td>


          <!-- ADDRESS -->
          <td class="px-6 py-4 text-gray-500">

            <div>
              {{ order.address?.street }}
            </div>

            <div class="text-xs text-gray-400">
              {{ order.address?.district }},
              {{ order.address?.city }}
            </div>

          </td>


          <!-- ITEMS -->
          <td class="px-6 py-4 text-center">
            {{ order.items?.length }}
          </td>


          <!-- TOTAL -->
          <td class="px-6 py-4 text-right font-medium">
            Rp {{ formatPrice(order.total_price) }}
          </td>


          <!-- DELIVERED AT -->
          <td class="px-6 py-4 text-center text-gray-500">

            {{
              order.delivery?.delivered_at
              ? formatDate(order.delivery.delivered_at)
              : '-'
            }}

          </td>


          <!-- STATUS -->
          <td class="px-6 py-4 text-center">

            <span
              class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700"
            >
              Delivered
            </span>

          </td>


          <!-- DETAIL -->
          <td class="px-6 py-4 text-center">

            <RouterLink
              :to="`/courier/history/${order.id}`"
              class="bg-slate-900 text-white text-xs px-3 py-1 rounded-lg hover:bg-slate-800"
            >
              Detail
            </RouterLink>

          </td>

        </tr>



        <!-- EMPTY -->
        <tr v-if="!loading && orders.length === 0">
          <td colspan="8" class="text-center py-8 text-gray-400">
            No delivery history
          </td>
        </tr>

      </tbody>

    </table>

  </div>

</div>
</template>



<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/api/axios'

const orders = ref([])
const loading = ref(false)



/*
|--------------------------------------------------------------------------
| FETCH HISTORY
|--------------------------------------------------------------------------
*/

const fetchHistory = async () => {

  loading.value = true

  try {

    const res = await axios.get('/courier/history')

    orders.value = res.data.data || []

  } catch (err) {

    console.error(err)

  }

  loading.value = false
}



/*
|--------------------------------------------------------------------------
| FORMAT
|--------------------------------------------------------------------------
*/

const formatPrice = (value) => {
  return new Intl.NumberFormat('id-ID').format(value)
}


const formatDate = (date) => {
  return new Date(date).toLocaleString('id-ID')
}


onMounted(fetchHistory)
</script>