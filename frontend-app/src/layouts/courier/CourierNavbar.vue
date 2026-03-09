<template>
  <header class="bg-white shadow px-4 py-3 flex justify-between items-center">
    <!-- Hamburger mobile -->
    <button class="md:hidden text-xl" @click="$emit('toggleSidebar')">☰</button>

    <h1 class="font-semibold text-lg">Courier Panel</h1>

    <div class="flex items-center gap-4">
      <span class="text-sm text-gray-600">{{ user?.name || 'Courier' }}</span>
      <button @click="logout" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
        Logout
      </button>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()
const user = authStore.user

const logout = () => {
  authStore.logout()
  router.push('/login')
}
</script>