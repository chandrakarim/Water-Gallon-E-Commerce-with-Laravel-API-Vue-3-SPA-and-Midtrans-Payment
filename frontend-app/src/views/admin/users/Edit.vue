<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div>
      <h2 class="text-2xl font-bold">Edit User</h2>
      <p class="text-gray-500 text-sm">Update user information</p>
    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
      <form @submit.prevent="updateUser" class="space-y-4">

        <!-- Name -->
        <div>
          <label class="block text-sm mb-1">Name</label>
          <input v-model="form.name"
                 type="text"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm mb-1">Email</label>
          <input v-model="form.email"
                 type="email"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Phone -->
        <div>
          <label class="block text-sm mb-1">Phone</label>
          <input v-model="form.phone"
                 type="text"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Role -->
        <div>
          <label class="block text-sm mb-1">Role</label>
          <select v-model="form.role"
                  class="w-full border rounded-lg px-3 py-2"
                  required>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
            <option value="courier">Courier</option>
          </select>
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

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
          <RouterLink
            to="/admin/users"
            class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50 transition"
          >
            Cancel
          </RouterLink>

          <button
            type="submit"
            class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600 transition"
          >
            Update User
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useUserStore } from '@/stores/user' // pastikan sudah ada stores/user.js

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()

const form = reactive({
  id: null,
  name: '',
  email: '',
  phone: '',
  role: '',
})

const loading = reactive({ value: false })

onMounted(async () => {
  loading.value = true
  try {
    // Ambil data user dari store (API)
    await userStore.fetchUser(route.params.id)

    if (userStore.user) {
      Object.assign(form, userStore.user)
    }
  } catch (err) {
    console.error('Fetch User Error:', err)
    alert('Failed to load user data.')
    router.push('/admin/users')
  } finally {
    loading.value = false
  }
})

async function updateUser() {
  loading.value = true
  try {
    await userStore.updateUser(form.id, {
      name: form.name,
      email: form.email,
      phone: form.phone,
      role: form.role
    })

    alert('User Updated Successfully')
    router.push('/admin/users')
  } catch (err) {
    console.error('Update User Error:', err)
    alert('Failed to update user.')
  } finally {
    loading.value = false
  }
}
</script>