<template>
  <div class="space-y-6">

    <!-- 🔹 HEADER -->
    <div>
      <h2 class="text-2xl font-bold">Add New User</h2>
      <p class="text-gray-500 text-sm">Create a new system account</p>
    </div>

    <!-- 🔹 FORM -->
    <div class="bg-white p-6 rounded-xl shadow max-w-3xl">
      <form @submit.prevent="submitForm" class="space-y-4">

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
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
            <option value="courier">Courier</option>
          </select>
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm mb-1">Password</label>
          <input v-model="form.password"
                 type="password"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="block text-sm mb-1">Confirm Password</label>
          <input v-model="form.confirmPassword"
                 type="password"
                 class="w-full border rounded-lg px-3 py-2"
                 required />
        </div>

        <!-- Status -->
        <div>
          <label class="block text-sm mb-1">Status</label>
          <select v-model="form.status"
                  class="w-full border rounded-lg px-3 py-2">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-4">
          <RouterLink
            to="/admin/users"
            class="px-4 py-2 rounded-lg border text-sm"
          >
            Cancel
          </RouterLink>

          <button
            type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700"
          >
            Save User
          </button>
        </div>

      </form>
    </div>

  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = reactive({
  name: '',
  email: '',
  phone: '',
  role: '',
  password: '',
  confirmPassword: '',
  status: 'active'
})

function submitForm() {
  if (form.password !== form.confirmPassword) {
    alert('Password does not match!')
    return
  }

  console.log('User Data:', form)

  // 🔥 nanti ganti dengan API call Laravel
  alert('User Created Successfully')

  router.push('/admin/users')
}
</script>