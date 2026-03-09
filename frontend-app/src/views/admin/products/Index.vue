<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold">Products</h2>
        <p class="text-gray-500 text-sm">Manage water gallon products</p>
      </div>

      <RouterLink
        to="/admin/products/create"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition"
      >
        + Add Product
      </RouterLink>
    </div>

    <!-- FILTER -->
    <div class="bg-white p-4 rounded-xl shadow flex gap-4">
      <input
        v-model="search"
        type="text"
        placeholder="Search product..."
        class="border rounded-lg px-3 py-2 w-1/3"
      />

      <select
        v-model="categoryFilter"
        class="border rounded-lg px-3 py-2 w-1/4"
      >
        <option value="">All Category</option>
        <option value="refill">Refill</option>
        <option value="new">New Gallon</option>
      </select>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
          <tr class="text-left">
            <th class="p-3">No</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <!-- <th>Status</th> -->
            <th class="text-center">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(product, index) in filteredProducts"
            :key="product.id"
            class="border-b hover:bg-gray-50"
          >
            <td class="p-3">{{ index + 1 }}</td>

            <td>
              <img
                :src="product.image"
                alt="Product Image"
                class="w-12 h-12 object-cover rounded"
              />
            </td>

            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>Rp {{ product.price }}</td>
            <td>{{ product.stock }}</td>

            <!-- <td>
              <span
                class="px-2 py-1 rounded text-xs"
                :class="product.status === 'active'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'"
              >
                {{ product.status }}
              </span>
            </td> -->
            <td class="text-center">
              <div class="flex justify-center gap-2">
                <!-- DETAIL -->
                <RouterLink
                  :to="`/admin/products/${product.id}`"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                >
                  👁 Detail
                </RouterLink>

                <!-- EDIT -->
                <RouterLink
                  :to="`/admin/products/edit/${product.id}`"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition"
                >
                  ✏ Edit
                </RouterLink>

                <!-- TOGGLE -->
                <button
                  @click="toggleStatus(product)"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-purple-50 text-purple-600 hover:bg-purple-100 transition"
                >
                  🔄 Toggle
                </button>

                <!-- DELETE -->
                <button
                  @click="deleteProduct(product.id)"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-red-50 text-red-600 hover:bg-red-100 transition"
                >
                  🗑 Delete
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="filteredProducts.length === 0">
            <td colspan="8" class="p-6 text-center text-gray-400">
              No products found
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/api/axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const products = ref([])
const search = ref('')
const categoryFilter = ref('')

// 🔥 Ambil data dari Laravel API
async function fetchProducts() {
  try {
    const res = await api.get('/admin/products')
    products.value = res.data.data
  } catch (err) {
    console.error(err)
  }
}

// 🔥 Filter products
const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(search.value.toLowerCase())
    const matchCategory = categoryFilter.value ? p.category === categoryFilter.value : true
    return matchSearch && matchCategory
  })
})

// 🔥 Toggle status dummy (bisa di-connect API nanti)
function toggleStatus(product) {
  product.status = product.status === 'active' ? 'inactive' : 'active'
}

// 🔥 Delete product dummy (bisa di-connect API nanti)
function deleteProduct(id) {
  if (confirm('Are you sure to delete this product?')) {
    products.value = products.value.filter(p => p.id !== id)
  }
}

// Jalankan saat component mounted
onMounted(() => fetchProducts())
</script>