import { defineStore } from 'pinia'
import api from '@/api/axios'
import router from '@/router'

export const useAuthStore = defineStore('auth', {

  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    isLogin: (state) => !!state.token
  },

  actions: {

    async login(form) {

      const response = await api.post('/auth/login', form)

      const token = response.data.data.token
      const user = response.data.data.user

      this.token = token
      this.user = user

      localStorage.setItem('token', token)

      if (user.role === 'admin') {
        router.push('/admin/dashboard')
      }

      if (user.role === 'customer') {
        router.push('/customer/products')
      }

      if (user.role === 'courier') {
        router.push('/courier/dashboard')
      }

    },

    async fetchUser() {

      try {

        const response = await api.get('/me')

        this.user = response.data.data

      } catch (error) {

        this.logout()

      }

    },

    async logout() {

      await api.post('/logout')

      this.$reset()

      localStorage.removeItem('token')

      router.replace('/')

    }

  }

})