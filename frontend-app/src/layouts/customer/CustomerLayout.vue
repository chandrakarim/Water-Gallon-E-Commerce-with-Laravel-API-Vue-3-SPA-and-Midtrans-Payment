<template>

<nav class="bg-white border-b sticky top-0 z-50">

<div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

<!-- LOGO -->
<router-link 
to="/customer/products" 
class="text-2xl font-bold text-blue-600 flex items-center gap-2"
>
💧 Water Gallon
</router-link>

<!-- MENU -->
<div class="hidden md:flex items-center gap-8 text-gray-600 font-medium">

<router-link 
to="/customer/products" 
class="flex items-center gap-2 hover:text-blue-600 transition"
>
🛍️ Produk
</router-link>

<router-link 
to="/customer/orders" 
class="flex items-center gap-2 hover:text-blue-600 transition"
>
📦 Pesanan
</router-link>

</div>

<!-- RIGHT SIDE -->
<div class="flex items-center gap-6">

<!-- CART -->
<router-link
to="/customer/cart"
class="relative text-2xl hover:scale-110 transition"
>

🛒

<span
v-if="cartStore.totalQty"
class="absolute -top-2 -right-3 bg-red-500 text-white text-xs font-semibold px-1.5 py-0.5 rounded-full"
>
{{ cartStore.totalQty }}
</span>

</router-link>

<!-- USER DROPDOWN -->
<div class="relative">

<button
@click="toggleMenu"
class="flex items-center gap-2 bg-gray-100 px-3 py-1.5 rounded-lg hover:bg-gray-200 transition"
>
👤
<span class="text-sm font-medium">
{{ authStore.user?.name }}
</span>
</button>

<!-- DROPDOWN -->
<div
v-if="menu"
class="absolute right-0 mt-3 bg-white shadow-lg rounded-lg w-48 border overflow-hidden"
>

<router-link
to="/customer/profile"
class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100"
>
⚙️ Profile
</router-link>

<router-link
to="/customer/addresses"
class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100"
>
📍 Address
</router-link>

<router-link
to="/customer/orders"
class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100"
>
📦 Orders
</router-link>

<button
@click="logout"
class="flex items-center gap-2 w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500"
>
🚪 Logout
</button>

</div>

</div>

</div>

</div>

</nav>

<main class="bg-gray-50 min-h-screen p-6">
<router-view />
</main>

</template>

<script setup>

import { ref,onMounted } from "vue"
import { useAuthStore } from "@/stores/auth"
import { useCartStore } from "@/stores/cart"

const authStore = useAuthStore()
const cartStore = useCartStore()

const menu = ref(false)

const toggleMenu = () => {
menu.value = !menu.value
}

const logout = () => {
authStore.logout()
}

onMounted(()=>{
cartStore.getCart()
})

</script>