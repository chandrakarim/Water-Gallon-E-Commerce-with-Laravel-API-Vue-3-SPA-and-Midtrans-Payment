<template>
  <footer class="bg-gray-900 text-gray-300 mt-16">

    <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">

      <!-- BRAND -->
      <div>
        <h2 class="text-xl font-bold text-white mb-3">
          Awatter Gallon
        </h2>
        <p class="text-sm">
          Layanan pesan antar air galon cepat,
          higienis, dan terpercaya langsung
          ke rumah Anda.
        </p>
      </div>

      <!-- MENU -->
      <div>
        <h3 class="font-semibold text-white mb-3">
          Menu
        </h3>

        <ul class="space-y-2 text-sm">
          <li>
            <router-link to="/" class="hover:text-white">
              Home
            </router-link>
          </li>

          <li>
            <router-link to="/products" class="hover:text-white">
              Produk
            </router-link>
          </li>

          <li>
            <router-link to="/about" class="hover:text-white">
              Tentang
            </router-link>
          </li>

          <li>
            <router-link to="/contact" class="hover:text-white">
              Kontak
            </router-link>
          </li>
        </ul>
      </div>

      <!-- KONTAK -->
      <div>
        <h3 class="font-semibold text-white mb-3">
          Kontak
        </h3>

        <p class="text-sm">
          📍 Indonesia
        </p>

        <p class="text-sm">
          📞 0812-3456-7890
        </p>

        <p class="text-sm">
          ✉ awatter@gmail.com
        </p>
      </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="border-t border-gray-700 text-center py-4 text-sm">
      © 2026 Awatter Gallon. All rights reserved.
    </div>

  </footer>
</template>