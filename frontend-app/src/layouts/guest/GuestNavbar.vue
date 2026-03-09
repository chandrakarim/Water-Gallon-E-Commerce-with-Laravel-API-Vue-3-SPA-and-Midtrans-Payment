<template>
  <nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">

      <!-- LOGO -->
      <div class="flex items-center gap-2">
        <img src="/images/gallon-hero.png" class="w-8 h-8" />
        <span class="text-xl font-bold text-blue-600">
          Awatter
        </span>
      </div>

      <!-- DESKTOP MENU -->
      <div class="hidden md:flex items-center gap-6">

        <router-link to="/" class="hover:text-blue-600">
          Home
        </router-link>

        <router-link to="/products" class="hover:text-blue-600">
          Produk
        </router-link>

        <router-link to="/about" class="hover:text-blue-600">
          Tentang
        </router-link>

        <router-link to="/contact" class="hover:text-blue-600">
          Kontak
        </router-link>

        <router-link
          to="/login"
          class="px-4 py-2 border rounded hover:bg-gray-100"
        >
          Login
        </router-link>

        <router-link
          to="/register"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
          Register
        </router-link>

      </div>

      <!-- HAMBURGER -->
      <button
        class="md:hidden"
        @click="toggleMenu"
      >
        ☰
      </button>

    </div>

    <!-- MOBILE MENU -->
    <div
      v-if="menuOpen"
      class="md:hidden border-t bg-white"
    >
      <div class="flex flex-col px-4 py-4 gap-4">

        <router-link @click="toggleMenu" to="/">
          Home
        </router-link>

        <router-link @click="toggleMenu" to="/products">
          Produk
        </router-link>

        <router-link @click="toggleMenu" to="/about">
          Tentang
        </router-link>

        <router-link @click="toggleMenu" to="/contact">
          Kontak
        </router-link>

        <router-link
          @click="toggleMenu"
          to="/login"
          class="border px-4 py-2 rounded text-center"
        >
          Login
        </router-link>

        <router-link
          @click="toggleMenu"
          to="/register"
          class="bg-blue-600 text-white px-4 py-2 rounded text-center"
        >
          Register
        </router-link>

      </div>
    </div>

  </nav>
</template>

<script setup>
import { ref } from "vue"

const menuOpen = ref(false)

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}
</script>