<template>
<div class="flex items-center justify-center min-h-screen bg-gray-100">

  <div class="bg-white p-8 rounded-xl shadow w-96">

    <h2 class="text-2xl font-bold mb-6 text-center">
      Register Customer
    </h2>

    <p v-if="error" class="text-red-500 mb-4">
      {{ error }}
    </p>

    <form @submit.prevent="register">

      <!-- NAME -->
      <div class="mb-4">
        <label class="block mb-1">Nama</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>

      <!-- EMAIL -->
      <div class="mb-4">
        <label class="block mb-1">Email</label>
        <input
          v-model="form.email"
          type="email"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>

      <!-- PHONE -->
      <div class="mb-4">
        <label class="block mb-1">No HP</label>
        <input
          v-model="form.phone"
          type="text"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>

      <!-- PASSWORD -->
      <div class="mb-4">
        <label class="block mb-1">Password</label>
        <input
          v-model="form.password"
          type="password"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>

      <!-- PASSWORD CONFIRM -->
      <div class="mb-4">
        <label class="block mb-1">Konfirmasi Password</label>
        <input
          v-model="form.password_confirmation"
          type="password"
          class="w-full border px-3 py-2 rounded"
          required
        />
      </div>

      <button
        class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
      >
        Register
      </button>

    </form>

  </div>

</div>
</template>

<script setup>
import { reactive, ref } from "vue"
import { useRouter } from "vue-router"
import axios from "@/api/axios"

const router = useRouter()

const error = ref(null)

const form = reactive({
  name: "",
  email: "",
  phone: "",
  password: "",
  password_confirmation: ""
})

const register = async () => {

  error.value = null

  try {

    await axios.post("/auth/register", form)

    alert("Registrasi berhasil")

    router.push("/login")

  } catch (err) {

    if (err.response?.data?.errors) {

      const errors = err.response.data.errors
      error.value = Object.values(errors).flat().join(", ")

    } else {

      error.value = err.response?.data?.message || "Registrasi gagal"

    }

  }

}
</script>