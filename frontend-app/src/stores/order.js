import { defineStore } from 'pinia'
import orderApi from '@/api/order.api'

export const useOrderStore = defineStore('order', {
  state: () => ({
    orders: [],      // wajib array kosong
    order: null,
    loading: false
  }),
  actions: {
    async fetchOrders() {
      this.loading = true
      try {
        const res = await orderApi.getAll()
        this.orders = res.data?.data || []  // pastikan selalu array
      } catch (error) {
        console.error('Fetch Orders Error:', error)
        this.orders = [] // fallback
      } finally {
        this.loading = false
      }
    },
    async fetchOrder(id) {
      try {
        const res = await orderApi.getById(id)
        this.order = res.data?.data || null
      } catch (error) {
        console.error('Fetch Order Error:', error)
        this.order = null
      }
    }
  }
})