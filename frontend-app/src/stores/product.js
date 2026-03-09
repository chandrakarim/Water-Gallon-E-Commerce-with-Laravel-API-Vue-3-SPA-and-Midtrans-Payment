import { defineStore } from 'pinia'
import productApi from '@/api/product.api'

export const useProductStore = defineStore('product', {
  state: () => ({
    products: [],
    product: null,
    loading: false
  }),

  actions: {
    async fetchProducts() {
      this.loading = true
      const res = await productApi.getAll()
      this.products = res.data.data
      this.loading = false
    },

    async fetchProduct(id) {
      const res = await productApi.getById(id)
      this.product = res.data.data
    },

    // 🔥 TAMBAHKAN INI
    async createProduct(data) {
      return await productApi.create(data)
    },

    async updateProduct(id, data) {
      return await productApi.update(id, data)
    },

    async fetchPublicProducts() {
      this.loading = true

      try {

        const response = await productApi.getPublic()

        this.products = response.data.data

      } catch (error) {

        console.error('Gagal ambil produk publik', error)

      } finally {

        this.loading = false

      }
    }
  }
})