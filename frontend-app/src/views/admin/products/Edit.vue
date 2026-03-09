<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div>
      <h2 class="text-2xl font-bold">Edit Product</h2>
      <p class="text-gray-500 text-sm">Update product information</p>
    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
      <form @submit.prevent="updateProduct" class="space-y-4">

        <!-- Image -->
        <div>
          <label class="block text-sm mb-1">Product Image</label>
          <input type="file"
                 @change="handleImage"
                 class="w-full border rounded-lg px-3 py-2" />

          <div v-if="preview" class="mt-3">
            <img :src="preview"
                 class="w-32 h-32 object-cover rounded-lg border" />
          </div>
        </div>
        

        <!-- Name -->
        <div>
          <label class="block text-sm mb-1">Product Name</label>
          <input v-model="form.name"
                 type="text"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Category -->
        <!-- <div>
          <label class="block text-sm mb-1">Category</label>
          <select v-model="form.category"
                  class="w-full border rounded-lg px-3 py-2"
                  required>
            <option value="refill">Refill</option>
            <option value="new">New Gallon</option>
          </select>
        </div> -->

        <!-- Price -->
        <div>
          <label class="block text-sm mb-1">Price</label>
          <input v-model="form.price"
                 type="number"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Stock -->
        <div>
          <label class="block text-sm mb-1">Stock</label>
          <input v-model="form.stock"
                 type="number"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Status -->
        <!-- <div>
          <label class="block text-sm mb-1">Status</label>
          <select v-model="form.status"
                  class="w-full border rounded-lg px-3 py-2">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div> -->

        <!-- Description -->
        <div>
          <label class="block text-sm mb-1">Description</label>
          <textarea v-model="form.description"
            rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
          <RouterLink
            to="/admin/products"
            class="px-4 py-2 rounded-lg border text-sm">
            Cancel
          </RouterLink>

          <button
            type="submit"
            class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">
            Update Product
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useProductStore } from '@/stores/product'

const route = useRoute()
const router = useRouter()
const productStore = useProductStore()


const preview = ref(null)

const imageFile = ref(null)

const form = reactive({
  name: '',
  price: '',
  stock: '',
  description: ''
})

onMounted(async () => {
  await productStore.fetchProduct(route.params.id)

  const product = productStore.product

  Object.assign(form, {
    name: product.name,
    price: product.price,
    stock: product.stock,
    description: product.description
  })

  // 🔥 tampilkan foto lama
  if (product.image) {
    preview.value = product.image
  }
})

function handleImage(e) {
  const file = e.target.files[0]
  imageFile.value = file

  if (file) {
    preview.value = URL.createObjectURL(file)
  }
}

async function updateProduct() {
  try {
    const formData = new FormData()

    formData.append('name', form.name)
    formData.append('price', form.price)
    formData.append('stock', form.stock)
    formData.append('description', form.description)

    if (imageFile.value) {
      formData.append('image', imageFile.value)
    }

    await productStore.updateProduct(route.params.id, formData)

    router.push('/admin/products')

  } catch (error) {
    console.error(error)
  }
}
</script>