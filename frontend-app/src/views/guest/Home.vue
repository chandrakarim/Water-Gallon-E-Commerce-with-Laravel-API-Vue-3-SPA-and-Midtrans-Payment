<template>
  <div>

    <!-- HERO -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-500 text-white py-20">
      <div class="max-w-7xl mx-auto grid md:grid-cols-2 items-center gap-10 px-6">

        <!-- TEXT -->
        <div>
          <div class="flex items-center gap-3 mb-4">
            <img
              src="/images/gallon-hero.png"
              alt="Awatter"
              class="w-12 h-12"
            />
            <span class="text-2xl font-bold">Awatter</span>
          </div>

          <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
            Pesan Air Galon <br />
            Dengan Mudah & Cepat
          </h1>

          <p class="text-lg mb-6 opacity-90">
            Awatter menyediakan layanan pengantaran air galon berkualitas
            langsung ke rumah Anda dengan kurir terpercaya.
          </p>

          <router-link
            to="/products"
            class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 transition"
          >
            Lihat Produk
          </router-link>
        </div>

        <!-- HERO IMAGE -->
        <div class="flex justify-center">
          <img
            src="/images/gallon-hero.png"
            class="w-80 drop-shadow-xl"
          />
        </div>

      </div>
    </section>

    <!-- FITUR -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-6">

        <h2 class="text-3xl font-bold text-center mb-12">
          Kenapa Memilih Kami
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

          <div class="bg-white shadow-md p-8 rounded-xl text-center hover:shadow-lg transition">
            <div class="text-4xl mb-4">🚚</div>
            <h3 class="font-semibold text-lg mb-2">
              Pengiriman Cepat
            </h3>
            <p class="text-gray-600">
              Kurir kami siap mengantar galon ke rumah Anda dengan cepat.
            </p>
          </div>

          <div class="bg-white shadow-md p-8 rounded-xl text-center hover:shadow-lg transition">
            <div class="text-4xl mb-4">💧</div>
            <h3 class="font-semibold text-lg mb-2">
              Air Berkualitas
            </h3>
            <p class="text-gray-600">
              Air galon berkualitas tinggi dan higienis untuk keluarga Anda.
            </p>
          </div>

          <div class="bg-white shadow-md p-8 rounded-xl text-center hover:shadow-lg transition">
            <div class="text-4xl mb-4">💰</div>
            <h3 class="font-semibold text-lg mb-2">
              Harga Terjangkau
            </h3>
            <p class="text-gray-600">
              Harga galon yang ramah di kantong dengan kualitas terbaik.
            </p>
          </div>

        </div>

      </div>
    </section>

  <!-- PRODUK SLIDER -->
<section class="py-16 bg-gray-50">
  <div class="max-w-7xl mx-auto px-6 relative">

    <h2 class="text-3xl font-bold text-center mb-10">
      Produk Populer
    </h2>

    <!-- BUTTON LEFT -->
    <button
      @click="scrollLeft"
      class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-lg rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-100"
    >
      ←
    </button>

    <!-- BUTTON RIGHT -->
    <button
      @click="scrollRight"
      class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-lg rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-100"
    >
      →
    </button>

    <!-- SLIDER -->
    <div
      ref="slider"
      class="flex gap-6 overflow-x-hidden scroll-smooth"
    >

      <div
        v-for="product in products"
        :key="product.id"
        class="min-w-[250px] bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition"
      >
        <img
          :src="getImage(product.image)"
          class="h-40 w-full object-cover rounded mb-4"
        />

        <h3 class="font-semibold text-lg mb-1">
          {{ product.name }}
        </h3>

        <p class="text-gray-500 text-sm mb-2 line-clamp-2">
          {{ product.description }}
        </p>

        <p class="font-bold text-blue-600 mb-3">
          Rp {{ formatPrice(product.price) }}
        </p>

        <router-link
          to="/login"
          class="text-sm bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Login untuk beli
        </router-link>

      </div>

    </div>

  </div>
</section>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue"
import productApi from "@/api/product.api"

const products = ref([])
const slider = ref(null)

let autoSlide = null

const formatPrice = (price) => {
  return new Intl.NumberFormat("id-ID").format(price)
}

const getImage = (image) => {
  return image
    ? `http://localhost:8000/storage/${image}`
    : "/images/no-image.png"
}

const loadProducts = async () => {
  try {
    const res = await productApi.getPublic()
    products.value = res.data.data
  } catch (error) {
    console.error("Gagal mengambil produk", error)
  }
}

const scrollLeft = () => {
  slider.value.scrollBy({
    left: -300,
    behavior: "smooth"
  })
}

const scrollRight = () => {
  slider.value.scrollBy({
    left: 300,
    behavior: "smooth"
  })
}

const startAutoSlide = () => {
  autoSlide = setInterval(() => {
    scrollRight()
  }, 4000)
}

onMounted(() => {
  loadProducts()
  startAutoSlide()
})

onUnmounted(() => {
  clearInterval(autoSlide)
})
</script>