<template>

<div v-if="order" class="space-y-6">

  <!-- HEADER -->
  <div class="flex justify-between items-center">

    <div>
      <h2 class="text-2xl font-bold">
        {{ order.invoice_number }}
      </h2>

      <p class="text-gray-500 text-sm">
        Order Date :
        {{ formatDate(order.ordered_at) }}
      </p>
    </div>

    <RouterLink
      to="/courier/assigned-orders"
      class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition"
    >
      ← Back
    </RouterLink>

  </div>



  <!-- STATUS -->
  <div class="bg-white rounded-xl shadow border p-6 flex justify-between items-center">

    <div>
      <p class="text-sm text-gray-500">Status</p>
      <p class="font-semibold">
        {{ order.status_label }}
      </p>
    </div>

    <span
      :class="statusBadge(order.status?.order)"
      class="px-3 py-1 text-xs rounded-full font-medium"
    >
      {{ order.status?.order }}
    </span>

  </div>



  <!-- CUSTOMER + ADDRESS -->
  <div class="grid md:grid-cols-2 gap-6">

    <!-- CUSTOMER -->
    <div class="bg-white rounded-xl shadow border p-6">

      <h3 class="font-semibold text-gray-700 mb-4">
        Customer Information
      </h3>

      <div class="space-y-2 text-sm text-gray-600">

        <p>
          <strong>Name :</strong>
          {{ order.user?.name || '-' }}
        </p>

        <p>
          <strong>Email :</strong>
          {{ order.user?.email || '-' }}
        </p>

        <p>
          <strong>Phone :</strong>
          {{ order.user?.phone || '-' }}
        </p>

      </div>

    </div>



    <!-- ADDRESS -->
    <div class="bg-white rounded-xl shadow border p-6">

      <h3 class="font-semibold text-gray-700 mb-4">
        Delivery Address
      </h3>

      <div class="text-sm space-y-1 text-gray-600">

        <p>
          {{ order.address?.full_address || '-' }}
        </p>

        <p>
          {{ order.address?.district }},
          {{ order.address?.city }}
        </p>

        <p>
          {{ order.address?.province }}
        </p>

        <p v-if="order.address?.notes">
          <strong>Notes :</strong>
          {{ order.address.notes }}
        </p>

      </div>

    </div>

  </div>



  <!-- ORDER ITEMS -->
  <div class="bg-white rounded-xl shadow border overflow-hidden">

    <div class="p-6 border-b">
      <h3 class="font-semibold text-gray-700">
        Order Items
      </h3>
    </div>

    <div class="overflow-x-auto">

      <table class="w-full text-sm">

        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
          <tr>
            <th class="px-6 py-3 text-left">Product</th>
            <th class="px-6 py-3 text-center">Qty</th>
            <th class="px-6 py-3 text-right">Price</th>
            <th class="px-6 py-3 text-right">Subtotal</th>
          </tr>
        </thead>

        <tbody class="divide-y">

          <tr
            v-for="item in order.items"
            :key="item.id"
            class="hover:bg-gray-50 transition"
          >

            <td class="px-6 py-4">
              {{ item.name }}
            </td>

            <td class="px-6 py-4 text-center">
              {{ item.qty }}
            </td>

            <td class="px-6 py-4 text-right">
              Rp {{ formatPrice(item.price) }}
            </td>

            <td class="px-6 py-4 text-right font-medium">
              Rp {{ formatPrice(item.subtotal) }}
            </td>

          </tr>

          <tr v-if="!order.items || order.items.length === 0">
            <td colspan="4" class="text-center py-6 text-gray-400">
              No items
            </td>
          </tr>

        </tbody>

      </table>

    </div>

  </div>



  <!-- PAYMENT + SUMMARY -->
  <div class="grid md:grid-cols-2 gap-6">

    <!-- PAYMENT -->
    <div class="bg-white rounded-xl shadow border p-6">

      <h3 class="font-semibold text-gray-700 mb-4">
        Payment Information
      </h3>

      <div class="text-sm text-gray-600 space-y-2">

        <p>
          <strong>Method :</strong>
          {{ order.payment?.method || '-' }}
        </p>

        <p>
          <strong>Bank :</strong>
          {{ order.payment?.bank || '-' }}
        </p>

        <p>
          <strong>VA Number :</strong>
          {{ order.payment?.va_number || '-' }}
        </p>

      </div>

    </div>



    <!-- TOTAL -->
    <div class="bg-white rounded-xl shadow border p-6">

      <h3 class="font-semibold text-gray-700 mb-4">
        Order Summary
      </h3>

      <div class="flex justify-between text-sm mb-2">
        <span>Total Items</span>
        <span>{{ order.items?.length || 0 }}</span>
      </div>

      <div class="flex justify-between text-lg font-semibold text-gray-800">
        <span>Total Price</span>
        <span>
          Rp {{ formatPrice(order.total_price) }}
        </span>
      </div>

    </div>

  </div>



  <!-- COURIER ACTION -->
  <div class="bg-white rounded-xl shadow border p-6 space-y-4">

    <h3 class="font-semibold text-gray-700">
      Courier Action
    </h3>



    <!-- START DELIVERY -->
    <button
      v-if="order.status?.order === 'ASSIGNED'"
      @click="startDelivery"
      class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg text-sm transition"
    >
      Start Delivery
    </button>



    <!-- DELIVERY FORM -->
    <div
      v-if="order.status?.order === 'PICKED_UP'"
      class="space-y-4"
    >

      <div>
        <label class="text-sm text-gray-600">
          Upload Delivery Photo
        </label>

        <input
          type="file"
          @change="handleFile"
          class="w-full border rounded-lg p-2 mt-1 text-sm"
        />
      </div>


      <div>
        <label class="text-sm text-gray-600">
          Delivery Notes
        </label>

        <textarea
          v-model="notes"
          rows="3"
          class="w-full border rounded-lg p-3 mt-1 text-sm"
        ></textarea>
      </div>


      <button
        @click="markDelivered"
        class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm transition"
      >
        Mark Delivered
      </button>

    </div>

  </div>


</div>

</template>



<script setup>

import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '@/api/axios'



const route = useRoute()
const router = useRouter()



const order = ref(null)
const notes = ref('')
const deliveryPhoto = ref(null)



/*
FETCH DETAIL
*/

const fetchDetail = async () => {

  try {

    const res = await axios.get(
      `/courier/orders/${route.params.id}`
    )

    order.value = res.data.data

  } catch (err) {

    console.error('Fetch detail error:', err)

  }

}



/*
START DELIVERY
*/

const startDelivery = async () => {

  if (!confirm('Start delivery?')) return

  try {

    await axios.patch(
      `/courier/orders/${order.value.id}/pickup`
    )

    await fetchDetail()

  } catch (err) {

    console.error('Start delivery error:', err)

  }

}



/*
HANDLE FILE
*/

const handleFile = (e) => {

  deliveryPhoto.value = e.target.files[0]

}



/*
MARK DELIVERED
*/

const markDelivered = async () => {

  if (!confirm('Mark order delivered?')) return

  try {

    const formData = new FormData()

    formData.append('_method', 'PATCH')
    formData.append('delivery_photo', deliveryPhoto.value)
    formData.append('notes', notes.value)

    await axios.post(
      `/courier/orders/${order.value.id}/deliver`,
      formData
    )

    router.push('/courier/assigned-orders')

  } catch (err) {

    console.error('Deliver error:', err)

  }

}



/*
FORMAT PRICE
*/

const formatPrice = (value) => {

  if (!value) return '0'

  return new Intl.NumberFormat('id-ID').format(value)

}



/*
FORMAT DATE
*/

const formatDate = (date) => {

  if (!date) return '-'

  return new Date(date).toLocaleDateString('id-ID')

}



/*
STATUS BADGE
*/

const statusBadge = (status) => {

  switch (status) {

    case 'ASSIGNED':
      return 'bg-yellow-100 text-yellow-700'

    case 'PICKED_UP':
      return 'bg-blue-100 text-blue-700'

    case 'DELIVERED':
      return 'bg-green-100 text-green-700'

    default:
      return 'bg-gray-100 text-gray-700'

  }

}



onMounted(fetchDetail)

</script>