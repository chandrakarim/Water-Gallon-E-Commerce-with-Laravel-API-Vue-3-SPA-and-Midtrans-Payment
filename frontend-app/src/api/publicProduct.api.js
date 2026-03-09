import api from './axios'

export default {

  getAll() {
    return api.get('/public/products')
  },

  getById(id) {
    return api.get(`/public/products/${id}`)
  }

}