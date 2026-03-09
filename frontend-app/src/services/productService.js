import axios from 'axios'

export const getProducts = () => {
  return axios.get('/admin/products')
}

export const getProduct = (id) => {
  return axios.get(`/admin/products/${id}`)
}

export const updateProduct = (id, data) => {
  return axios.post(`/admin/products/${id}?_method=PUT`, data, {
    headers: { 'Content-Type': 'multipart/form-data' }
  })
}