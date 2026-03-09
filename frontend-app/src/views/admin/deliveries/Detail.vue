<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-2xl font-bold">Delivery Detail</h2>
        <p class="text-gray-500 text-sm">
          Detail informasi pengiriman
        </p>
      </div>

      <button
        @click="$router.back()"
        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm"
      >
        ← Back
      </button>
    </div>


    <!-- LOADING -->
    <div
      v-if="loading"
      class="text-center py-12 text-gray-400"
    >
      Loading delivery detail...
    </div>


    <!-- CONTENT -->
    <div
      v-else-if="delivery"
      class="grid grid-cols-1 lg:grid-cols-3 gap-6"
    >

      <!-- LEFT SIDE -->
      <div class="lg:col-span-2 space-y-6">


        <!-- ORDER INFO -->
        <div class="bg-white rounded-xl shadow p-6 space-y-4">

          <h3 class="font-semibold text-lg">
            Order Information
          </h3>

          <div class="grid grid-cols-2 gap-4 text-sm">

            <div>
              <p class="text-gray-500">Invoice</p>
              <p class="font-semibold">
                {{ delivery.invoice_number }}
              </p>
            </div>

            <div>
              <p class="text-gray-500">Status</p>
              <span
                class="px-3 py-1 rounded text-xs"
                :class="statusColor(delivery.status)"
              >
                {{ delivery.status_label }}
              </span>
            </div>

            <div>
              <p class="text-gray-500">Payment Status</p>
              <span
                class="px-3 py-1 rounded text-xs"
                :class="paymentColor(delivery.payment_status)"
              >
                {{ delivery.payment_status }}
              </span>
            </div>

            <div>
              <p class="text-gray-500">Courier</p>
              <p class="font-semibold">
                {{ delivery.courier_name || '-' }}
              </p>
            </div>

            <!-- TAMBAHAN -->
            <div>
              <p class="text-gray-500">Courier Phone</p>
              <p class="font-semibold">
                {{ delivery.courier_phone || '-' }}
              </p>
            </div>

            <div>
              <p class="text-gray-500">Payment Method</p>
              <p class="font-semibold">
                {{ delivery.payment_method || '-' }}
              </p>
            </div>

          </div>
        </div>


        <!-- CUSTOMER -->
        <div class="bg-white rounded-xl shadow p-6 space-y-3">

          <h3 class="font-semibold text-lg">
            Customer
          </h3>

          <div class="text-sm space-y-1">

            <p class="font-semibold">
              {{ delivery.customer_name }}
            </p>

            <p class="text-gray-500">
              {{ delivery.customer_phone || '-' }}
            </p>

          </div>

        </div>


        <!-- ADDRESS -->
        <div class="bg-white rounded-xl shadow p-6 space-y-3">

          <h3 class="font-semibold text-lg">
            Delivery Address
          </h3>

          <p class="text-sm font-semibold whitespace-pre-line">
            {{ delivery.address }}
          </p>

          <!-- TAMBAHAN MAP -->
          <a
            v-if="delivery.address"
            :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(delivery.address)}`"
            target="_blank"
            class="text-xs text-blue-500 hover:underline"
          >
            Open in Google Maps
          </a>

        </div>


        <!-- ORDER ITEMS -->
        <div class="bg-white rounded-xl shadow p-6">

          <h3 class="font-semibold text-lg mb-4">
            Order Items
          </h3>

          <table class="w-full text-sm">

            <thead>
              <tr class="border-b text-gray-500">
                <th class="text-left pb-2">Product</th>
                <th class="text-center pb-2">Qty</th>
                <th class="text-right pb-2">Price</th>
                <th class="text-right pb-2">Subtotal</th>
              </tr>
            </thead>

            <tbody>

              <tr
                v-for="item in delivery.items"
                :key="item.id"
                class="border-b"
              >

                <td class="py-2">
                  {{ item.name }}
                </td>

                <td class="text-center">
                  {{ item.qty }}
                </td>

                <td class="text-right">
                  Rp {{ formatPrice(item.price) }}
                </td>

                <td class="text-right">
                  Rp {{ formatPrice(item.subtotal) }}
                </td>

              </tr>

              <tr v-if="!delivery.items || delivery.items.length === 0">
                <td
                  colspan="4"
                  class="text-center text-gray-400 py-6"
                >
                  No order items
                </td>
              </tr>

            </tbody>

          </table>

          <div class="text-right mt-4 font-semibold">
            Total :
            Rp {{ formatPrice(delivery.total_price) }}
          </div>

        </div>


        <!-- DELIVERY INFO -->
        <div class="bg-white rounded-xl shadow p-6 space-y-4">

          <h3 class="font-semibold text-lg">
            Delivery Information
          </h3>

          <div
            v-if="delivery.delivery"
            class="space-y-3 text-sm"
          >

            <div>
              <p class="text-gray-500">Picked At</p>
              <p class="font-semibold">
                {{ formatDate(delivery.delivery.picked_at) || '-' }}
              </p>
            </div>

            <div>
              <p class="text-gray-500">Delivered At</p>
              <p class="font-semibold">
                {{ formatDate(delivery.delivery.delivered_at) || '-' }}
              </p>
            </div>

            <div>
              <p class="text-gray-500">Notes</p>
              <p class="font-semibold">
                {{ delivery.delivery.notes || '-' }}
              </p>
            </div>

          </div>

          <div
            v-else
            class="text-gray-400 text-sm"
          >
            Delivery data not available
          </div>

        </div>


        <!-- TIMELINE -->
        <div class="bg-white rounded-xl shadow p-6">

          <h3 class="font-semibold text-lg mb-4">
            Tracking Timeline
          </h3>

          <div v-if="sortedHistories.length">

            <div
              v-for="history in sortedHistories"
              :key="history.id"
              class="border-l-2 border-gray-300 pl-4 mb-4 relative"
            >

              <div
                class="absolute -left-2 top-1 w-3 h-3 bg-blue-500 rounded-full"
              ></div>

              <p class="text-sm font-semibold">
                {{ history.status }}
              </p>

              <p class="text-xs text-gray-500">
                {{ formatDate(history.created_at) }}
              </p>

            </div>

          </div>

          <div
            v-else
            class="text-gray-400 text-sm"
          >
            No tracking history
          </div>

        </div>

      </div>


      <!-- RIGHT SIDE -->
      <div class="bg-white rounded-xl shadow p-6">

        <h3 class="font-semibold text-lg mb-4">
          Delivery Photo
        </h3>

        <div v-if="delivery.delivery?.delivery_photo">

          <img
            :src="delivery.delivery.delivery_photo"
            class="rounded-lg w-full object-cover cursor-pointer hover:opacity-80 transition"
            @click="openImage(delivery.delivery.delivery_photo)"
          />

          <p class="text-xs text-gray-400 text-center mt-2">
            Click image to enlarge
          </p>

        </div>

        <div
          v-else
          class="h-64 flex items-center justify-center bg-gray-100 rounded-lg text-gray-400 text-sm"
        >
          No photo available
        </div>

      </div>

    </div>


    <!-- IMAGE MODAL -->
    <div
      v-if="imageModal"
      class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
      @click.self="imageModal = null"
    >

      <img
        :src="imageModal"
        class="max-h-[90%] max-w-[90%] rounded-lg shadow-lg"
      />

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue"
import { useRoute } from "vue-router"
import axios from "@/api/axios"

const route = useRoute()

const delivery = ref(null)
const loading = ref(false)
const imageModal = ref(null)

/* =========================
   STATUS COLOR
========================= */
function statusColor(status) {
  switch (status) {
    case "ASSIGNED":
      return "bg-blue-100 text-blue-700"

    case "PICKED_UP":
      return "bg-yellow-100 text-yellow-700"

    case "DELIVERED":
      return "bg-green-100 text-green-700"

    default:
      return "bg-gray-100 text-gray-700"
  }
}

/* =========================
   PAYMENT COLOR
========================= */
function paymentColor(status) {
  switch (status) {
    case "PAID":
      return "bg-green-100 text-green-700"

    case "UNPAID":
      return "bg-red-100 text-red-700"

    default:
      return "bg-gray-100 text-gray-700"
  }
}

/* =========================
   FORMAT DATE
========================= */
function formatDate(date) {
  if (!date) return null
  return new Date(date).toLocaleString("id-ID")
}

/* =========================
   FORMAT PRICE
========================= */
function formatPrice(price) {
  if (!price) return 0
  return Number(price).toLocaleString("id-ID")
}

/* =========================
   IMAGE MODAL
========================= */
function openImage(src) {
  imageModal.value = src
}

/* =========================
   SORT TRACKING HISTORY
========================= */
const sortedHistories = computed(() => {
  if (!delivery.value?.histories) return []

  return [...delivery.value.histories].sort(
    (a, b) => new Date(a.created_at) - new Date(b.created_at)
  )
})

/* =========================
   FETCH DELIVERY DETAIL
========================= */
async function fetchDetail() {
  loading.value = true

  try {

    const res = await axios.get(
      `/admin/deliveries/${route.params.id}`
    )

    delivery.value = res.data.data

  } catch (error) {

    console.error("Delivery detail error:", error)

  }

  loading.value = false
}

/* =========================
   ON MOUNT
========================= */
onMounted(() => {
  fetchDetail()
})
</script>