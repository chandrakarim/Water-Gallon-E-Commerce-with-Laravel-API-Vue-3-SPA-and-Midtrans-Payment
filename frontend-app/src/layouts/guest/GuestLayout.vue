<template>
  <div class="flex flex-col min-h-screen bg-gray-50">

    <GuestNavbar />

    <main class="flex-grow">
      <router-view />
    </main>

    <GuestFooter />

  </div>
</template>

<script setup>
import GuestNavbar from "./GuestNavbar.vue"
import GuestFooter from "./GuestFooter.vue"
</script>