<template>
  <div class="space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-2xl font-bold">User Detail</h2>
        <p class="text-gray-500 text-sm">View user information</p>
      </div>

      <RouterLink
        to="/admin/users"
        class="border px-4 py-2 rounded-lg text-sm hover:bg-gray-100 transition"
      >
        ← Back
      </RouterLink>
    </div>

    <!-- LOADING -->
    <div v-if="loading" class="bg-white p-6 rounded-xl shadow text-center">
      <p class="text-gray-500">Loading user...</p>
    </div>

    <!-- CONTENT -->
    <div v-else-if="user" class="bg-white p-6 rounded-xl shadow max-w-3xl">

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
          <p class="text-sm text-gray-500">Name</p>
          <p class="font-semibold mt-1">{{ user.name }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Email</p>
          <p class="font-semibold mt-1">{{ user.email }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Phone</p>
          <p class="font-semibold mt-1">{{ user.phone || '-' }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Role</p>
          <p class="font-semibold mt-1 capitalize">{{ user.role }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Status</p>
          <span
            class="px-2 py-1 rounded text-xs font-medium"
            :class="user.status === 'active'
              ? 'bg-green-100 text-green-700'
              : 'bg-red-100 text-red-700'"
          >
            {{ user.status }}
          </span>
        </div>

        <div>
          <p class="text-sm text-gray-500">Created At</p>
          <p class="font-semibold mt-1">{{ user.created_at }}</p>
        </div>

      </div>

      <!-- ACTION -->
      <div class="mt-8">
        <RouterLink
          :to="`/admin/users/edit/${user.id}`"
          class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg
                 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition"
        >
          ✏ Edit User
        </RouterLink>
      </div>

    </div>

    <!-- NOT FOUND -->
    <div v-else class="bg-white p-6 rounded-xl shadow text-center text-gray-400">
      User not found
    </div>

  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'

const route = useRoute()
const userStore = useUserStore()

const loading = computed(() => userStore.loading)
const user = computed(() => userStore.user)

onMounted(async () => {
  await userStore.fetchUser(route.params.id)
})
</script>