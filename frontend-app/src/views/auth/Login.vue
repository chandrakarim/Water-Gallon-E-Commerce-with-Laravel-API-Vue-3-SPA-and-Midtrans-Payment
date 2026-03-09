<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-blue-700">
    <div class="bg-white w-96 p-8 rounded-2xl shadow-2xl">
      
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Welcome Back 👋</h2>
        <p class="text-gray-500 text-sm">Silakan login ke akun Anda</p>
      </div>

      <div class="space-y-4">
        <div>
          <label class="text-sm text-gray-600">Email</label>
          <input
            v-model="email"
            type="email"
            placeholder="Masukkan email"
            class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
          />
        </div>

        <div>
          <label class="text-sm text-gray-600">Password</label>
          <input
            v-model="password"
            type="password"
            placeholder="Masukkan password"
            class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"
          />
        </div>

        <button
          @click="login"
          class="w-full bg-blue-600 hover:bg-blue-700 transition text-white py-2 rounded-lg font-semibold"
        >
          Login
        </button>
      </div>

      <p class="text-center text-xs text-gray-400 mt-6">
        © 2026 Water Gallon App
      </p>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import api from '@/api/axios'

const email = ref('')
const password = ref('')
const auth = useAuthStore()
const router = useRouter()

const login = async () => {
  try {
    const res = await api.post('/auth/login', {
      email: email.value,
      password: password.value
    })

    console.log("RESPONSE ASLI:", res.data)

    const user = res.data.data.user
    const token = res.data.data.token

    auth.user = user
    auth.token = token

    localStorage.setItem('token', token)

    // redirect sesuai role
    if (user.role === 'admin') {
      router.push('/admin/dashboard')
    } 
    else if (user.role === 'customer') {
      router.push('/customer/products')
    } 
    else if (user.role === 'courier') {
      router.push('/courier/dashboard')
    }

  } catch (error) {
    console.log("ERROR:", error.response?.data)
    alert(error.response?.data?.message || 'Login gagal')
  }
}
</script>