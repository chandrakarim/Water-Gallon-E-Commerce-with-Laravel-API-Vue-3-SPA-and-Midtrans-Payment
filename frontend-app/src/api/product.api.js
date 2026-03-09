import api from './axios'

export default {
  getAll() {
    return api.get('/admin/products')
  },

  getById(id) {
    return api.get(`/admin/products/${id}`)
  },

  create(data) {
    return api.post('/admin/products', data)
  },

  update(id, data) {
    return api.post(`/admin/products/${id}?_method=PUT`, data, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  },

  delete(id) {
    return api.delete(`/admin/products/${id}`)
  },

getPublic() {
    return api.get('/public/products') // 🔹 route publik
  }
  
}