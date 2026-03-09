<script setup>
import { ref, onMounted } from "vue"
import axios from "@/api/axios"
import { useRouter } from "vue-router"

const router = useRouter()
const address = ref(null)
const loading = ref(true)

const getAddress = async () => {
  try {
    const res = await axios.get("/addresses")

    if (res.data.data && res.data.data.length > 0) {
      address.value = res.data.data[0]
    } else {
      address.value = null
    }

  } catch (err) {
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  getAddress()
})

const goCreate = () => {
  router.push("/customer/addresses/create")
}

const goEdit = () => {
  router.push(`/customer/addresses/edit/${address.value.id}`)
}
</script>

<template>
<div class="max-w-3xl mx-auto py-10 px-4">

  <h1 class="text-2xl font-bold mb-6">
    Alamat Saya
  </h1>

  <div v-if="loading">
    Loading...
  </div>

  <!-- JIKA BELUM ADA ALAMAT -->
  <div v-if="!address && !loading"
       class="bg-white border rounded-xl p-6 shadow">

      <p class="text-gray-500 mb-4">
        Kamu belum menambahkan alamat.
      </p>

      <button
        @click="goCreate"
        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
      >
        Tambah Alamat
      </button>

  </div>

  <!-- JIKA SUDAH ADA ALAMAT -->
  <div v-if="address"
       class="bg-white border rounded-xl p-6 shadow space-y-3">

      <div class="flex justify-between items-center">

        <span class="bg-gray-100 px-3 py-1 text-sm rounded">
          {{ address.label }}
        </span>

        <button
          @click="goEdit"
          class="text-blue-600 font-medium"
        >
          Edit Alamat
        </button>

      </div>

      <div class="font-semibold">
        {{ address.recipient_name }} - {{ address.phone }}
      </div>

      <div class="text-gray-600">
        {{ address.address }}
      </div>

      <div v-if="address.note" class="text-sm text-gray-500">
        Catatan: {{ address.note }}
      </div>

  </div>

</div>
</template>