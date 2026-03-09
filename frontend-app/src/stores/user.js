import { defineStore } from 'pinia'
import userApi from '@/api/user.api'

export const useUserStore = defineStore('user', {
  state: () => ({
    users: [],
    user: null,
    loading: false
  }),

  actions: {

    async fetchUsers() {
      this.loading = true
      try {
        const res = await userApi.getAll()
        this.users = res.data.data
      } finally {
        this.loading = false
      }
    },

    async fetchUser(id) {
      this.loading = true
      try {
        const res = await userApi.getById(id)
        this.user = res.data.data
      } finally {
        this.loading = false
      }
    },

    async createUser(data) {
      return await userApi.create(data)
    },

    async updateUser(id, data) {
      return await userApi.update(id, data)
    },

    async deleteUser(id) {
      return await userApi.delete(id)
    }

  }
})