<template>
  <div class="space-y-6">

    <!-- 🔹 HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold">Users</h2>
        <p class="text-gray-500 text-sm">Manage all system users</p>
      </div>

      <RouterLink
        to="/admin/users/create"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700"
      >
        + Add User
      </RouterLink>
    </div>

    <!-- 🔹 FILTER -->
    <div class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row gap-4">
      <input
        v-model="search"
        type="text"
        placeholder="Search name or email..."
        class="border rounded-lg px-3 py-2 w-full md:w-1/3"
      />

      <select
        v-model="roleFilter"
        class="border rounded-lg px-3 py-2 w-full md:w-1/4"
      >
        <option value="">All Roles</option>
        <option value="admin">Admin</option>
        <option value="customer">Customer</option>
        <option value="courier">Courier</option>
      </select>
    </div>

    <!-- 🔹 LOADING -->
    <div v-if="loading" class="bg-white p-6 rounded-xl shadow text-center">
      <p class="text-gray-500">Loading users...</p>
    </div>

    <!-- 🔹 TABLE -->
    <div v-else class="bg-white rounded-xl shadow overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
          <tr class="text-left">
            <th class="p-3">No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Phone</th>
            <!-- <th>Status</th> -->
            <th>Created</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="(user, index) in filteredUsers"
            :key="user.id"
            class="border-b hover:bg-gray-50"
          >
            <td class="p-3">{{ index + 1 }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td class="capitalize">{{ user.role }}</td>
            <td>{{ user.phone || '-' }}</td>

            <!-- <td>
              <span
                class="px-2 py-1 rounded text-xs"
                :class="user.status === 'active'
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-700'"
              >
                {{ user.status }}
              </span>
            </td> -->

            <td>{{ user.created_at }}</td>

        <td class="text-center">
              <div class="flex justify-center gap-2">

                <!-- DETAIL -->
                <RouterLink
                  :to="`/admin/users/${user.id}`"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                >
                  👁 Detail
                </RouterLink>

                <!-- EDIT -->
                <RouterLink
                  :to="`/admin/users/edit/${user.id}`"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition"
                >
                  ✏ Edit
                </RouterLink>

                <!-- DELETE -->
                <button
                  @click="removeUser(user.id)"
                  class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg
                        bg-red-50 text-red-600 hover:bg-red-100 transition"
                >
                  🗑 Delete
                </button>

              </div>
            </td>
          </tr>

          <tr v-if="filteredUsers.length === 0">
            <td colspan="8" class="text-center p-6 text-gray-400">
              No users found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useUserStore } from '@/stores/user'

const userStore = useUserStore()

const search = ref('')
const roleFilter = ref('')

const loading = computed(() => userStore.loading)
const users = computed(() => userStore.users)

onMounted(() => {
  userStore.fetchUsers()
})

const filteredUsers = computed(() => {
  return users.value.filter(user => {

    const matchSearch =
      user.name?.toLowerCase().includes(search.value.toLowerCase()) ||
      user.email?.toLowerCase().includes(search.value.toLowerCase())

    const matchRole =
      roleFilter.value === '' || user.role === roleFilter.value

    return matchSearch && matchRole
  })
})

async function removeUser(id) {
  if (!confirm('Delete this user?')) return

  await userStore.deleteUser(id)
  await userStore.fetchUsers()
}
</script>