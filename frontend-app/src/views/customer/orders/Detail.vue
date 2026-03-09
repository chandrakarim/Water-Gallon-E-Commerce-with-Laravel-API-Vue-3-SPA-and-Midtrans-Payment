<template>
<div class="max-w-5xl mx-auto p-6 space-y-6">

  <!-- HEADER ORDER -->
  <div class="bg-white shadow rounded-xl p-6 flex flex-col md:flex-row md:justify-between md:items-center gap-4">

    <div>
      <h1 class="text-lg font-semibold text-gray-800">
        Order #{{ order.invoice_number }}
      </h1>

      <p class="text-sm text-gray-500">
        Status :
        <span class="font-medium text-blue-600">
          {{ order.status_label }}
        </span>
      </p>
    </div>

    <router-link
      :to="`/customer/orders/${order.id}/tracking`"
      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm"
    >
      🚚 Lihat Tracking
    </router-link>

  </div>

  <!-- LIST PRODUK -->
  <div class="bg-white shadow rounded-xl divide-y">

    <div
      v-for="item in order.items"
      :key="item.id"
      class="p-4 flex items-center justify-between"
    >

      <div class="flex items-center gap-4">

        <!-- IMAGE -->
        <img
          :src="item.image_url || '/images/gallon-hero.png'"
          class="w-16 h-16 object-cover rounded"
        />

        <!-- INFO -->
        <div>
          <p class="font-medium text-gray-800">
            {{ item.name }}
          </p>

          <p class="text-sm text-gray-500">
            Qty : {{ item.qty }}
          </p>

          <p class="text-sm text-gray-500">
            Harga : Rp {{ formatPrice(item.price) }}
          </p>
        </div>

      </div>

      <!-- SUBTOTAL -->
      <div class="text-right">

        <p class="font-semibold text-gray-800">
          Rp {{ formatPrice(item.subtotal) }}
        </p>

      </div>

    </div>

  </div>

  <!-- SUMMARY -->
  <div class="bg-white shadow rounded-xl p-6">

    <div class="flex justify-between text-sm mb-2">
      <span>Total Item</span>
      <span>{{ order.total_qty }}</span>
    </div>

    <div class="flex justify-between text-sm mb-2">
      <span>Subtotal</span>
      <span>Rp {{ formatPrice(order.total_price) }}</span>
    </div>

    <div class="border-t pt-3 mt-3 flex justify-between font-semibold text-lg">

      <span>Total Pembayaran</span>

      <span class="text-blue-600">
        Rp {{ formatPrice(order.total_price) }}
      </span>

    </div>

  </div>

</div>
</template>

<script setup>

import { ref, onMounted } from "vue"
import axios from "@/api/axios"
import { useRoute } from "vue-router"

const route = useRoute()

const order = ref({
  items: []
})

const load = async () => {

  try {

    const res = await axios.get(`/orders/${route.params.id}`)

    order.value = res.data.data

  } catch (err) {

    console.error(err)

  }

}

const formatPrice = (price) => {

  return new Intl.NumberFormat("id-ID").format(price)

}

onMounted(load)

</script>