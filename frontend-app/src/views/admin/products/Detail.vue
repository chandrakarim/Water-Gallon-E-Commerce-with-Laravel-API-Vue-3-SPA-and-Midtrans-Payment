<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold">Product Detail</h2>
        <p class="text-gray-500 text-sm">View product information</p>
      </div>

      <RouterLink
        to="/admin/products"
        class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-100"
      >
        ← Back
      </RouterLink>
    </div>

    <!-- LOADING -->
    <div v-if="!product" class="bg-white p-6 rounded-xl shadow text-center">
      <p class="text-gray-500">Loading product...</p>
    </div>

    <!-- CONTENT -->
    <div v-else class="bg-white p-6 rounded-xl shadow max-w-4xl">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- IMAGE -->
        <div>
          <img
            v-if="product.image"
            :src="product.image"
            class="w-full max-w-sm rounded-xl border object-cover"
          />
          <div v-else class="w-full max-w-sm h-60 bg-gray-100 flex items-center justify-center rounded-xl">
            <span class="text-gray-400 text-sm">No Image</span>
          </div>
        </div>

        <!-- INFO -->
        <div class="space-y-4">

          <div>
            <p class="text-sm text-gray-500">Product Name</p>
            <p class="font-semibold text-lg mt-1">
              {{ product.name }}
            </p>
          </div>

          <div>
            <p class="text-sm text-gray-500">Price</p>
            <p class="font-semibold mt-1">
              Rp {{ product.price }}
            </p>
          </div>

          <div>
            <p class="text-sm text-gray-500">Stock</p>
            <p class="font-semibold mt-1">
              {{ product.stock }}
            </p>
          </div>

          <div>
            <p class="text-sm text-gray-500">Description</p>
            <p class="mt-1 text-gray-700">
              {{ product.description }}
            </p>
          </div>

        </div>
      </div>

      <!-- EDIT BUTTON -->
      <div class="mt-8">
        <RouterLink
          :to="`/admin/products/edit/${product.id}`"
          class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600"
        >
          Edit Product
        </RouterLink>
      </div>

    </div>

  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useProductStore } from '@/stores/product'

const route = useRoute()
const productStore = useProductStore()

const product = computed(() => productStore.product)

onMounted(async () => {
  await productStore.fetchProduct(route.params.id)
})
</script>