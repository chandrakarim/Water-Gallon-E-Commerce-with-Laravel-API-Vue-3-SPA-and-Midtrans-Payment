<script setup>
import { ref, onMounted } from "vue"
import axios from "@/api/axios"
import { useRoute, useRouter } from "vue-router"

const route = useRoute()
const router = useRouter()

const form = ref({
  label: "",
  recipient_name: "",
  phone: "",
  province: "",
  city: "",
  district: "",
  detail: "",
  note: ""
})

const getAddress = async () => {
  try {
    const res = await axios.get(`/addresses/${route.params.id}`)
    form.value = res.data.data
  } catch (err) {
    console.error(err)
    alert("Gagal mengambil data alamat")
  }
}

const update = async () => {
  try {
    await axios.put(`/addresses/${route.params.id}`, form.value)
    alert("Alamat berhasil diupdate")
    router.push("/customer/addresses")
  } catch (err) {
    console.error(err)
    alert("Update gagal")
  }
}

onMounted(() => {
  getAddress()
})
</script>

<template>
<div class="max-w-2xl mx-auto py-10 px-4">

  <h1 class="text-2xl font-bold mb-6 text-gray-800">
    Edit Alamat
  </h1>

  <div class="bg-white shadow-lg rounded-xl p-6 space-y-5">

    <!-- Label Address -->
    <div>
      <label class="block mb-1 font-medium text-gray-700">Label Alamat <span class="text-red-500">*</span></label>
      <input 
        v-model="form.label" 
        type="text"
        placeholder="Misal: Rumah, Kantor"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      />
      <small class="text-gray-500 text-sm">Gunakan label untuk mengenali alamat ini dengan mudah</small>
    </div>

    <!-- Recipient Name -->
    <div>
      <label class="block mb-1 font-medium text-gray-700">Nama Penerima <span class="text-red-500">*</span></label>
      <input 
        v-model="form.recipient_name"
        type="text"
        placeholder="Nama penerima paket"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      />
      <small class="text-gray-500 text-sm">Pastikan sesuai dengan nama penerima</small>
    </div>

    <!-- Phone -->
    <div>
      <label class="block mb-1 font-medium text-gray-700">No. HP <span class="text-red-500">*</span></label>
      <input 
        v-model="form.phone"
        type="tel"
        placeholder="08123456789"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      />
      <small class="text-gray-500 text-sm">Nomor HP penerima untuk konfirmasi atau kurir</small>
    </div>

    <!-- Province, City, District -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="block mb-1 font-medium text-gray-700">Provinsi</label>
        <input 
          v-model="form.province"
          type="text"
          placeholder="DI Yogyakarta"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        />
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Kota/Kabupaten</label>
        <input 
          v-model="form.city"
          type="text"
          placeholder="Sleman"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        />
      </div>
      <div>
        <label class="block mb-1 font-medium text-gray-700">Kecamatan</label>
        <input 
          v-model="form.district"
          type="text"
          placeholder="Ngaglik"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        />
      </div>
    </div>

    <!-- Detail Address -->
    <div>
      <label class="block mb-1 font-medium text-gray-700">Detail Alamat</label>
      <textarea
        v-model="form.detail"
        placeholder="Jl. Merdeka No. 10, depan toko xyz"
        rows="2"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
      ></textarea>
      <small class="text-gray-500 text-sm">Alamat lengkap untuk kurir sampai tujuan</small>
    </div>

    <!-- Note -->
    <div>
      <label class="block mb-1 font-medium text-gray-700">Catatan Tambahan</label>
      <textarea
        v-model="form.note"
        placeholder="Misal: Pagar hitam, pintu samping"
        rows="2"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none"
      ></textarea>
      <small class="text-gray-500 text-sm">Informasi tambahan agar kurir lebih mudah menemukan alamat</small>
    </div>

    <!-- Button -->
    <button
      @click="update"
      class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold"
    >
      Update Alamat
    </button>

  </div>

</div>
</template>