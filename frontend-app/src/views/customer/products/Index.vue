<template>

<div class="max-w-7xl mx-auto py-10 px-4">

<h1 class="text-2xl font-bold mb-6">
Produk Galon
</h1>

<div class="grid md:grid-cols-3 gap-6">

<div
v-for="product in products"
:key="product.id"
class="bg-white shadow rounded p-4 flex flex-col"
>

<!-- IMAGE -->

<div
class="h-44 bg-gray-100 flex items-center justify-center rounded mb-3 cursor-zoom-in"
@click="openImage(product.image)"
>
<img
:src="product.image"
class="max-h-full object-contain p-3 transition hover:scale-105"
/>
</div>

<!-- PRODUCT INFO -->

<h2 class="font-semibold text-lg">
{{ product.name }}
</h2>

<p class="text-gray-500 text-sm mb-2">
{{ product.description }}
</p>

<p class="font-bold text-blue-600 mb-2">
Rp {{ format(product.price) }}
</p>

<p
v-if="product.stock > 0"
class="text-green-600 text-sm mb-2"
>
Stock: {{ product.stock }}
</p>

<p
v-else
class="text-red-500 text-sm mb-2"
>
Stok Habis
</p>

<!-- QTY CONTROL -->

<div
v-if="product.stock > 0"
class="flex items-center gap-2 mb-3"
>

<button
@click="decrease(product.id)"
class="px-3 py-1 bg-gray-200 rounded"
>
-
</button>

<span class="w-8 text-center">
{{ qty[product.id] || 1 }}
</span>

<button
@click="increase(product.id)"
class="px-3 py-1 bg-gray-200 rounded"
>
+
</button>

</div>

<!-- ADD CART -->

<button
@click="addCart(product.id)"
:disabled="product.stock == 0"
class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded disabled:bg-gray-400"
>
Add To Cart
</button>

</div>

</div>

</div>


<!-- IMAGE MODAL ZOOM -->

<div
v-if="showImage"
class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50"
@click="closeImage"
>

<img
:src="selectedImage"
class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-lg"
/>

</div>

</template>

<script setup>

import { ref,onMounted } from "vue"
import axios from "@/api/axios"
import { useCartStore } from "@/stores/cart"

const cartStore = useCartStore()

const products = ref([])
const qty = ref({})

/* IMAGE MODAL STATE */

const showImage = ref(false)
const selectedImage = ref(null)

const openImage = (img)=>{
selectedImage.value = img
showImage.value = true
}

const closeImage = ()=>{
showImage.value = false
}

/* GET PRODUCTS */

const getProducts = async()=>{

const res = await axios.get("/products")

products.value = res.data.data

}

onMounted(()=>{

getProducts()

})

const increase = (id)=>{

qty.value[id] = (qty.value[id] || 1) + 1

}

const decrease = (id)=>{

if((qty.value[id] || 1) <= 1) return

qty.value[id]--

}

const addCart = async(id)=>{

const quantity = qty.value[id] || 1

await cartStore.addCart(id,quantity)

alert("Produk ditambahkan ke cart")

}

const format = (number)=>{

return new Intl.NumberFormat("id-ID").format(number)

}

</script>