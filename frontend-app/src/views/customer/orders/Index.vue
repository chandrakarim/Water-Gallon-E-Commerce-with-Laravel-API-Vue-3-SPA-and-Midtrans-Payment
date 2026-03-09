<template>

<div class="max-w-6xl mx-auto">

<h1 class="text-2xl font-bold mb-6">
Pesanan Saya
</h1>

<div
v-for="order in orders"
:key="order.id"
class="bg-white p-4 rounded shadow mb-4"
>

<div class="flex justify-between">

<div>

<p class="font-semibold">
{{ order.invoice_number }}
</p>

<p class="text-gray-500">
{{ order.status_label }}
</p>

</div>

<router-link
:to="`/customer/orders/${order.id}`"
class="text-blue-600"
>

Detail

</router-link>

</div>

</div>

</div>

</template>

<script setup>

import { ref,onMounted } from "vue"
import axios from "@/api/axios"

const orders = ref([])

const load = async()=>{

const res = await axios.get("/orders")

orders.value = res.data.data

}

onMounted(load)

</script>