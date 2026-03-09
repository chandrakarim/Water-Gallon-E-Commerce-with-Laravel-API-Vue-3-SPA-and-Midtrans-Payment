<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'
import {
  HomeIcon,
  UsersIcon,
  CubeIcon,
  ShoppingCartIcon,
  TruckIcon,
  CreditCardIcon,
  Bars3Icon,
  XMarkIcon
} from '@heroicons/vue/24/outline'

const isOpen = ref(false)

const toggleSidebar = () => {
  isOpen.value = !isOpen.value
}
</script>

<template>
  <!-- MOBILE TOP BAR -->
  <div class="md:hidden flex items-center justify-between bg-gradient-to-r from-indigo-600 to-indigo-700 text-white p-4">
    <span class="font-bold text-lg">Watter Gallon</span>
    <button @click="toggleSidebar">
      <Bars3Icon class="w-6 h-6" />
    </button>
  </div>

  <!-- OVERLAY -->
  <div v-if="isOpen"
       @click="toggleSidebar"
       class="fixed inset-0 bg-black bg-opacity-40 z-40 md:hidden"></div>

  <!-- SIDEBAR -->
  <aside
    :class="[
      'fixed md:static z-50 top-0 left-0 h-full w-64 bg-gradient-to-b from-indigo-700 to-indigo-900 text-white shadow-xl transform transition-transform duration-300',
      isOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'
    ]"
  >

    <!-- HEADER -->
    <div class="p-6 flex justify-between items-center border-b border-indigo-500">
      <h2 class="text-xl font-bold">Watter Gallon</h2>
      <button class="md:hidden" @click="toggleSidebar">
        <XMarkIcon class="w-6 h-6" />
      </button>
    </div>

    <!-- MENU -->
    <nav class="p-4 space-y-2">

      <RouterLink to="/admin/dashboard" class="menu">
        <HomeIcon class="w-5 h-5" />
        <span>Dashboard</span>
      </RouterLink>

      <RouterLink to="/admin/users" class="menu">
        <UsersIcon class="w-5 h-5" />
        <span>Users</span>
      </RouterLink>

      <RouterLink to="/admin/products" class="menu">
        <CubeIcon class="w-5 h-5" />
        <span>Products</span>
      </RouterLink>

      <RouterLink to="/admin/orders" class="menu">
        <ShoppingCartIcon class="w-5 h-5" />
        <span>Orders</span>
      </RouterLink>

      <RouterLink to="/admin/deliveries" class="menu">
        <TruckIcon class="w-5 h-5" />
        <span>Deliveries</span>
      </RouterLink>

      <RouterLink to="/admin/payments" class="menu">
        <CreditCardIcon class="w-5 h-5" />
        <span>Payments</span>
      </RouterLink>

    </nav>

  </aside>
</template>

<style scoped>
.menu {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 12px;
  transition: all 0.2s ease;
}

.menu:hover {
  background: rgba(255,255,255,0.15);
  transform: translateX(5px);
}

.router-link-active {
  background: rgba(255,255,255,0.25);
  font-weight: 600;
}
</style>