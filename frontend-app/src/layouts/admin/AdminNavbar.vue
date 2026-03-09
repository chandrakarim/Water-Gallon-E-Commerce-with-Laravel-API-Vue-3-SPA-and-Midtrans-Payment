<script setup>
import { useAuthStore } from '@/stores/auth'
import { BellIcon } from '@heroicons/vue/24/outline'

const auth = useAuthStore()

const handleLogout = async () => {
  await auth.logout()
}
</script>

<template>
  <header class="bg-white shadow-lg px-6 py-4 flex justify-between items-center">

    <!-- TITLE -->
    <div>
      <h1 class="text-xl font-bold text-gray-800">Admin Dashboard</h1>
      <p class="text-sm text-gray-400">Welcome back 👋</p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center gap-6">

      <!-- Notification -->
      <div class="relative cursor-pointer">
        <BellIcon class="w-6 h-6 text-gray-600 hover:text-indigo-600 transition" />
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 rounded-full">
          3
        </span>
      </div>

      <!-- User -->
      <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
          A
        </div>
        <span class="text-gray-700 font-medium">Admin</span>
      </div>

      <!-- Logout -->
      <button
        @click="handleLogout"
        class="bg-gradient-to-r from-red-500 to-red-600 text-white px-4 py-2 rounded-lg shadow hover:scale-105 transition"
      >
        Logout
      </button>

    </div>

  </header>
</template>