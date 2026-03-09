<template>
  <div class="max-w-5xl mx-auto py-10 px-4">

    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    <!-- Alamat -->
    <div class="bg-white p-4 rounded shadow mb-6">
      <h2 class="font-semibold mb-2">Pilih Alamat</h2>
      <select v-model="address_id" class="border p-2 rounded w-full">
        <option v-for="addr in addresses" :key="addr.id" :value="addr.id">
          {{ addr.label }} - {{ addr.address }}
        </option>
      </select>
    </div>

    <!-- Ringkasan Keranjang -->
    <div class="bg-white p-4 rounded shadow mb-6">
      <h2 class="font-semibold mb-2">Ringkasan Pesanan</h2>
      <div v-for="item in cart" :key="item.id" class="flex justify-between py-2 border-b">
        <span>{{ item.name }} x {{ item.qty }}</span>
        <span>Rp {{ item.subtotal }}</span>
      </div>
      <div class="flex justify-between font-bold mt-4">
        <span>Total</span>
        <span>Rp {{ total }}</span>
      </div>
    </div>

    <!-- Pilihan Pembayaran -->
    <div class="bg-white p-4 rounded shadow mb-6">
      <h2 class="font-semibold mb-2">Metode Pembayaran</h2>
      <div class="flex flex-col md:flex-row gap-4">
        <label class="flex items-center gap-2">
          <input type="radio" value="COD" v-model="payment_method" />
          Bayar di Tempat (COD)
        </label>
        <label class="flex items-center gap-2">
          <input type="radio" value="MIDTRANS" v-model="payment_method" />
          Bayar Online (Midtrans)
        </label>
      </div>
    </div>

    <button
      class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition"
      @click="checkout"
      :disabled="!address_id || !payment_method"
    >
      Bayar Sekarang
    </button>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "@/api/axios"
import { useRouter } from "vue-router"

const router = useRouter()

const cart = ref([])
const total = ref(0)
const addresses = ref([])
const address_id = ref(null)
const payment_method = ref("COD")

const loadData = async () => {
  try {
    const cartRes = await axios.get("/cart")
    cart.value = cartRes.data.data.items
    total.value = cartRes.data.data.total_price

    const addrRes = await axios.get("/addresses")
    addresses.value = addrRes.data.data

    if (addresses.value.length) address_id.value = addresses.value[0].id
  } catch (err) {
    console.error(err)
  }
}

const checkout = async () => {
  if (!address_id.value || !payment_method.value) return alert("Pilih alamat & metode pembayaran!")

  try {
    const res = await axios.post("/orders/checkout", {
      address_id: address_id.value,
      payment_method: payment_method.value
    })

    if (payment_method.value === "MIDTRANS") {
      // redirect ke Midtrans payment
      window.location.href = res.data.data.payment.payment_url
    } else {
      router.push("/customer/orders")
    }
  } catch (err) {
    console.error(err)
    alert("Checkout gagal")
  }
}

onMounted(loadData)
</script>