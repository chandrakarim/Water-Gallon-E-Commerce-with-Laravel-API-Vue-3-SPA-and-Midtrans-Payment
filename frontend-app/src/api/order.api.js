import axios from './axios'

export default {
  getAll() {
    return axios.get('/admin/orders')
  },

  getById(id) {
    return axios.get(`admin/orders/${id}`)
  },

  updateStatus(id, status) {
    return axios.patch(`${API_URL}/${id}/status`, { status })
  },

  delete(id) {
    return axios.delete(`${API_URL}/${id}`)
  }
}