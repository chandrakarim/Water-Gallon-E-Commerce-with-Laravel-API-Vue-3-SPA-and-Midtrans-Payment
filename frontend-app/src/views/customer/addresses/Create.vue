<script setup>
import { ref } from "vue"
import axios from "@/api/axios"
import { useRouter } from "vue-router"

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

const submit = async () => {
  try {

    await axios.post("/addresses", form.value)

    alert("Alamat berhasil ditambahkan")

    router.push("/customer/addresses")

  } catch (err) {
    console.error(err)
    alert("Gagal menambahkan alamat")
  }
}
</script>

<template>
<div class="max-w-2xl mx-auto py-10 px-4">

<h1 class="text-2xl font-bold mb-6">
Tambah Alamat
</h1>

<div class="bg-white shadow rounded-xl p-6 space-y-4">

<input
v-model="form.label"
placeholder="Label Alamat (Rumah / Kantor)"
class="w-full border rounded-lg px-3 py-2"
/>

<input
v-model="form.recipient_name"
placeholder="Nama Penerima"
class="w-full border rounded-lg px-3 py-2"
/>

<input
v-model="form.phone"
placeholder="Nomor Telepon"
class="w-full border rounded-lg px-3 py-2"
/>

<input
v-model="form.province"
placeholder="Provinsi"
class="w-full border rounded-lg px-3 py-2"
/>

<input
v-model="form.city"
placeholder="Kota / Kabupaten"
class="w-full border rounded-lg px-3 py-2"
/>

<input
v-model="form.district"
placeholder="Kecamatan"
class="w-full border rounded-lg px-3 py-2"
/>

<textarea
v-model="form.detail"
placeholder="Detail alamat (jalan, nomor rumah)"
class="w-full border rounded-lg px-3 py-2"
/>

<textarea
v-model="form.note"
placeholder="Catatan (opsional)"
class="w-full border rounded-lg px-3 py-2"
/>

<button
@click="submit"
class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700"
>
Simpan Alamat
</button>

</div>

</div>
</template>