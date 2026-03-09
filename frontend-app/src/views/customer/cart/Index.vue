<template>
  <div class="max-w-5xl mx-auto py-10 px-4">

    <!-- HEADER -->
    <h1 class="text-2xl font-bold mb-6">Keranjang Saya</h1>

    <!-- CART ITEMS -->
    <div v-if="cart.length" class="space-y-4">

      <!-- ITEM -->
      <div
        v-for="item in cart"
        :key="item.id"
        class="bg-white rounded-xl shadow-sm border p-4 flex justify-between items-center"
      >

        <!-- LEFT SIDE -->
        <div class="flex items-center gap-4">

          <!-- PRODUCT IMAGE -->
          <img
            :src="item.image_url || 'https://via.placeholder.com/80'"
            class="w-20 h-20 object-cover rounded-lg border"
          />

          <!-- PRODUCT INFO -->
          <div>

            <p class="font-semibold text-gray-800">
              {{ item.name }}
            </p>

            <p class="text-sm text-gray-500">
              Rp {{ formatPrice(item.price) }}
            </p>

            <!-- QTY -->
            <div class="flex items-center mt-2 space-x-2">

              <button
                @click="updateQty(item, item.qty - 1)"
                class="w-7 h-7 flex items-center justify-center border rounded hover:bg-gray-100"
              >
                -
              </button>

              <span class="px-2 font-medium">
                {{ item.qty }}
              </span>

              <button
                @click="updateQty(item, item.qty + 1)"
                class="w-7 h-7 flex items-center justify-center border rounded hover:bg-gray-100"
              >
                +
              </button>

            </div>

          </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="flex flex-col items-end space-y-2">

          <span class="font-semibold text-gray-800">
            Rp {{ formatPrice(item.subtotal) }}
          </span>

          <button
            @click="removeItem(item)"
            class="text-red-500 text-sm hover:text-red-700"
          >
            Hapus
          </button>

        </div>

      </div>

      <!-- TOTAL -->
      <div class="bg-white rounded-xl shadow-sm border p-4 flex justify-between items-center">

        <span class="text-lg font-semibold">
          Total
        </span>

        <span class="text-xl font-bold text-blue-600">
          Rp {{ formatPrice(total) }}
        </span>

      </div>

      <!-- ACTION BUTTONS -->
      <div class="flex gap-3">

        <button
          @click="goShopping"
          class="flex-1 border py-3 rounded-xl hover:bg-gray-100"
        >
          Lanjut Belanja
        </button>

        <button
          @click="goToCheckout"
          class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition"
        >
          Checkout Sekarang
        </button>

      </div>

    </div>

    <!-- EMPTY CART -->
    <div
      v-else
      class="text-center py-20"
    >
      <p class="text-gray-500 text-lg mb-4">
        Keranjang Anda masih kosong
      </p>

      <button
        @click="goShopping"
        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
      >
        Belanja Sekarang
      </button>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "@/api/axios"
import { useRouter } from "vue-router"

const router = useRouter()

const cart = ref([])
const total = ref(0)

const loadCart = async () => {

  try {

    const res = await axios.get("/cart")

    cart.value = res.data.data.items || []
    total.value = res.data.data.total_price || 0

  } catch (err) {

    console.error("Load cart error:", err)

  }

}

const updateQty = async (item, qty) => {

  if (qty < 1) {

    removeItem(item)
    return

  }

  try {

    await axios.patch(`/cart/${item.id}`, {
      qty: qty
    })

    await loadCart()

  } catch (err) {

    console.error("Update qty error:", err)

  }

}

const removeItem = async (item) => {

  if (!confirm("Hapus produk dari keranjang?")) return

  try {

    await axios.delete(`/cart/${item.id}`)

    await loadCart()

  } catch (err) {

    console.error("Delete cart item error:", err)

  }

}

const goShopping = () => {

  router.push("/customer/products")

}

const goToCheckout = async () => {

  try {

    const res = await axios.get("/addresses")

    const addresses = res.data.data

    if (!addresses || addresses.length === 0) {

      alert("Silakan isi alamat terlebih dahulu")

      router.push("/customer/addresses")

      return

    }

    router.push("/customer/checkout")

  } catch (err) {

    console.error("Check address error:", err)

    alert("Gagal mengambil alamat")

  }

}

const formatPrice = (price) => {
  return new Intl.NumberFormat("id-ID").format(price)
}

onMounted(loadCart)
</script>