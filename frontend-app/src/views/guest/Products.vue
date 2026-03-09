<template>
  <div class="max-w-7xl mx-auto py-10 px-4">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold mb-8">
      Produk Galon
    </h1>

    <!-- GRID -->
    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

      <div
        v-for="product in products"
        :key="product.id"
        class="bg-white shadow-md rounded-xl overflow-hidden hover:shadow-lg transition"
      >

        <!-- IMAGE -->
        <div class="h-44 bg-gray-100 flex items-center justify-center">
          <img
            :src="getImage(product.image)"
            :alt="product.name"
            class="max-h-full object-contain p-3"
          />
        </div>

        <!-- CONTENT -->
        <div class="p-4">

          <!-- NAME -->
          <h3 class="font-semibold text-lg mb-1">
            {{ product.name }}
          </h3>

          <!-- DESCRIPTION -->
          <p class="text-gray-500 text-sm mb-3 line-clamp-2">
            {{ product.description }}
          </p>

          <!-- PRICE -->
          <p class="font-bold text-blue-600 mb-4">
            Rp {{ formatPrice(product.price) }}
          </p>

          <!-- BUTTON -->
          <router-link
            to="/login"
            class="block text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
          >
            Login untuk membeli
          </router-link>

        </div>

      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import productApi from "../../api/product.api"

const products = ref([])

const getImage = (image) => {
  return image
    ? `http://localhost:8000/storage/${image}`
    : "/images/no-image.png"
}

const formatPrice = (price) => {
  return new Intl.NumberFormat("id-ID").format(price)
}

onMounted(async () => {
  try {
    const res = await productApi.getPublic()

    products.value = res.data.data

  } catch (error) {
    console.error("Gagal mengambil produk:", error)
  }
})
</script>