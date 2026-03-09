<script setup>
defineProps({ type: String })
</script>

<template>
  <button
    class="px-3 py-2 rounded-lg text-sm font-medium transition"
    :class="{
      'bg-blue-600 text-white hover:bg-blue-700': type==='primary',
      'bg-green-600 text-white hover:bg-green-700': type==='success',
      'bg-red-600 text-white hover:bg-red-700': type==='danger',
      'bg-gray-200 hover:bg-gray-300': type==='secondary'
    }"
  >
    <slot />
  </button>
</template>