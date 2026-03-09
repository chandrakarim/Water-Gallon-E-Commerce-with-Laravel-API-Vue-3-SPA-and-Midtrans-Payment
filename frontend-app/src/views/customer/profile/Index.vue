<template>
  <div class="max-w-2xl mx-auto py-10 px-4 space-y-10">

    <h1 class="text-2xl font-bold mb-6">Profile Saya</h1>

    <!-- ======= Update Profile ======= -->
    <div class="bg-white shadow rounded-xl p-6 space-y-4">
      <h2 class="text-lg font-semibold mb-2">Informasi Pribadi</h2>

      <div class="space-y-3">
        <div>
          <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
          <input v-model="form.name" type="text" placeholder="Nama lengkap"
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input v-model="form.email" type="email" placeholder="Email aktif"
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Nomor Telepon</label>
          <input v-model="form.phone" type="text" placeholder="0812xxxx"
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
        </div>

        <button @click="updateProfile"
          class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
          Simpan Profil
        </button>
      </div>
    </div>

    <!-- ======= Update Password ======= -->
    <div class="bg-white shadow rounded-xl p-6 space-y-4">
      <h2 class="text-lg font-semibold mb-2">Ubah Password</h2>

      <div class="space-y-3">
        <div>
          <label class="block text-sm font-medium mb-1">Password Saat Ini</label>
          <input v-model="passwordForm.current_password" type="password" placeholder="Password saat ini"
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Password Baru</label>
          <input v-model="passwordForm.new_password" type="password" placeholder="Minimal 8 karakter"
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Konfirmasi Password Baru</label>
          <input v-model="passwordForm.new_password_confirmation" type="password" placeholder="Ulangi password baru"
            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"/>
        </div>

        <button @click="updatePassword"
          class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
          Ubah Password
        </button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "@/api/axios"

const form = ref({
  name: "",
  email: "",
  phone: ""
})

const passwordForm = ref({
  current_password: "",
  new_password: "",
  new_password_confirmation: ""
})

// Load data user
const loadProfile = async () => {
  try {
    const res = await axios.get("/me")
    form.value = {
      name: res.data.data.name,
      email: res.data.data.email,
      phone: res.data.data.phone || ""
    }
  } catch (err) {
    console.error(err)
    alert("Gagal memuat data profil")
  }
}

// Update profile
const updateProfile = async () => {
  try {
    await axios.put("/me", form.value)
    alert("Profil berhasil diperbarui")
  } catch (err) {
    console.error(err)
    alert("Update profil gagal")
  }
}

// Update password
const updatePassword = async () => {
  try {
    await axios.put("/me/password", passwordForm.value)
    alert("Password berhasil diperbarui")
    // Reset form
    passwordForm.value.current_password = ""
    passwordForm.value.new_password = ""
    passwordForm.value.new_password_confirmation = ""
  } catch (err) {
    console.error(err)
    alert(err.response?.data?.message || "Update password gagal")
  }
}

onMounted(loadProfile)
</script>