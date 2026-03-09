<script setup>
import Sidebar from '@/layouts/admin/AdminSidebar.vue'
import Navbar from '@/layouts/admin/AdminNavbar.vue'
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <div class="flex flex-col flex-1">
      <Navbar />

      <main class="p-6 overflow-y-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>