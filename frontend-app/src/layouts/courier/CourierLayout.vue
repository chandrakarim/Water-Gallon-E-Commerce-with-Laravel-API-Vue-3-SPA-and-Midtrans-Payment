<template>
  <div class="flex min-h-screen bg-gray-100">

    <!-- Sidebar Desktop -->
    <aside class="hidden md:block w-64 bg-slate-900 text-white">
      <CourierSidebar />
    </aside>

    <!-- Main Area -->
    <div class="flex-1 flex flex-col">

      <!-- Navbar -->
      <CourierNavbar @toggleSidebar="showSidebar = !showSidebar" />

      <!-- Mobile Sidebar Overlay -->
      <transition name="fade">
        <div v-if="showSidebar" class="fixed inset-0 z-50 flex md:hidden">
          <div class="w-64 bg-slate-900 text-white shadow">
            <CourierSidebar @close="showSidebar = false" />
          </div>
          <div class="flex-1 bg-black/40" @click="showSidebar = false"></div>
        </div>
      </transition>

      <!-- Page Content -->
      <main class="flex-1 p-4 md:p-6 overflow-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import CourierSidebar from './CourierSidebar.vue'
import CourierNavbar from './CourierNavbar.vue'

const showSidebar = ref(false)
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>