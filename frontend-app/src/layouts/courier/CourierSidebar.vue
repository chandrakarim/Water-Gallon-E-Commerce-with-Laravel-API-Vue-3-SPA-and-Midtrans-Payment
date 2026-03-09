<template>
  <nav class="flex flex-col h-full p-4 space-y-2">
    <div class="text-xl font-bold border-b border-slate-700 pb-4 mb-4">Courier Panel</div>

    <RouterLink
      to="/courier/dashboard"
      class="block px-4 py-2 rounded hover:bg-slate-800 transition"
      :class="{ 'bg-slate-800 font-semibold': isActive('/courier/dashboard') }"
    >
      📊 Dashboard
    </RouterLink>

    <RouterLink
      to="/courier/assigned-orders"
      class="block px-4 py-2 rounded hover:bg-slate-800 transition"
      :class="{ 'bg-slate-800 font-semibold': isActive('/courier/assigned-orders') }"
    >
      📦 Assigned Orders
    </RouterLink>

    <RouterLink
      to="/courier/history"
      class="block px-4 py-2 rounded hover:bg-slate-800 transition"
      :class="{ 'bg-slate-800 font-semibold': isActive('/courier/history') }"
    >
      🕓 History
    </RouterLink>
  </nav>
</template>

<script setup>
import { useRoute } from 'vue-router'
const route = useRoute()
const isActive = path => route.path === path
</script>