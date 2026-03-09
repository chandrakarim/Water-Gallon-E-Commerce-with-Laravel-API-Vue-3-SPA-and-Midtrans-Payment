import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import AdminLayout from '@/layouts/admin/AdminLayout.vue'
import CourierLayout from '@/layouts/courier/CourierLayout.vue'
import CustomerLayout from '@/layouts/customer/CustomerLayout.vue'
// Layout Guest
import GuestLayout from '../layouts/guest/GuestLayout.vue'

// Guest Views
import Home from '../views/guest/Home.vue'
import Products from '../views/guest/Products.vue'
import About from '../views/guest/About.vue'
import Contact from '../views/guest/Contact.vue'

// Auth Views
import Login from '@/views/auth/Login.vue'
import Register from '@/views/auth/Register.vue'

const routes = [

  // ================= GUEST WEBSITE =================
  {
    path: '/',
    component: GuestLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: Home
      },
      {
        path: 'products',
        name: 'guest-products',
        component: Products
      },
      {
        path: 'about',
        name: 'about',
        component: About
      },
      {
        path: 'contact',
        name: 'contact',
        component: Contact
      },
      // ================= AUTH =================
      {
        path: 'login',
        component: Login,
        meta: { guest: true }
      },
      {
        path: 'register',
        component: Register,
        meta: { guest: true }
      },
    ]
  },

  // ================= ADMIN =================
  {
    path: '/admin',
    component: AdminLayout,
    meta: { role: 'admin' },
    redirect: '/admin/dashboard',
    children: [
      { path: 'dashboard', component: () => import('@/views/admin/dashboard/Dashboard.vue') },

      { path: 'users', component: () => import('@/views/admin/users/Index.vue') },
      { path: 'users/create', component: () => import('@/views/admin/users/Create.vue') },
      { path: 'users/edit/:id', component: () => import('@/views/admin/users/Edit.vue') },
      { path: 'users/:id', component: () => import('@/views/admin/users/Detail.vue') },

      { path: 'products', component: () => import('@/views/admin/products/Index.vue') },
      { path: 'products/create', component: () => import('@/views/admin/products/Create.vue') },
      { path: 'products/edit/:id', component: () => import('@/views/admin/products/Edit.vue') },
      { path: 'products/:id', component: () => import('@/views/admin/products/Detail.vue') },

      { path: 'orders', component: () => import('@/views/admin/orders/Index.vue') },
      { path: 'orders/:id', component: () => import('@/views/admin/orders/Detail.vue') },

      { path: 'deliveries', component: () => import('@/views/admin/deliveries/Index.vue') },
      { path: 'deliveries/:id', component: () => import('@/views/admin/deliveries/Detail.vue') },

      { path: 'payments', component: () => import('@/views/admin/payments/Index.vue') }
    ]
  },

// ================= CUSTOMER =================
{
  path: '/customer',
  component: CustomerLayout,
  meta: { role: 'customer' },
  redirect: '/customer/products',

  children: [

    // ================= PRODUCTS =================
    {
      path: 'products',
      component: () => import('@/views/customer/products/Index.vue')
    },

    // ================= CART =================
    {
      path: 'cart',
      component: () => import('@/views/customer/cart/Index.vue')
    },

    // ================= CHECKOUT =================
    {
      path: 'checkout',
      component: () => import('@/views/customer/checkout/Index.vue')
    },

    // ================= ORDERS =================
    {
      path: 'orders',
      component: () => import('@/views/customer/orders/Index.vue')
    },

    {
      path: 'orders/:id',
      component: () => import('@/views/customer/orders/Detail.vue'),
      props: true
    },

    {
      path: 'orders/:id/tracking',
      component: () => import('@/views/customer/tracking/Detail.vue'),
      props: true
    },

    // ================= ADDRESSES =================
    {
      path: 'addresses',
      component: () => import('@/views/customer/addresses/Index.vue')
    },

    {
      path: 'addresses/create',
      component: () => import('@/views/customer/addresses/Create.vue')
    },

    {
      path: "/customer/addresses/edit/:id",
      component: () => import("@/views/customer/addresses/Edit.vue")
    },

    // ================= PROFILE =================
    {
      path: 'profile',
      component: () => import('@/views/customer/profile/Index.vue')
    }

  ]
},

  // ================= COURIER =================
  {
    path: '/courier',
    component: CourierLayout,
    meta: { role: 'courier' },
    redirect: '/courier/dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/courier/dashboard/Dashboard.vue')
      },
      {
        path: 'assigned-orders',
        component: () => import('@/views/courier/assigned-orders/Index.vue')
      },
      {
        path: 'orders/:id',
        component: () => import('@/views/courier/assigned-orders/Detail.vue'),
        props: true
      },
      {
        path: 'history',
        component: () => import('@/views/courier/history/Index.vue')
      }
    ]
  }

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

/*
|--------------------------------------------------------------------------
| 🔐 GLOBAL AUTH + ROLE GUARD
|--------------------------------------------------------------------------
*/

router.beforeEach((to) => {
  const auth = useAuthStore()

  const isLoggedIn = !!auth.token
  const isLoginPage = to.path === '/login'

  // ===============================
  // 🚫 BELUM LOGIN
  // ===============================
  if (!isLoggedIn) {
    // hanya blok halaman yang butuh role
    if (to.matched.some(r => r.meta.role)) {
      return { path: '/login' }
    }
    return true
  }

  // ===============================
  // 🔁 SUDAH LOGIN TAPI BUKA LOGIN
  // ===============================
  if (isLoggedIn && isLoginPage) {
    const target = redirectByRole(auth.user?.role)

    if (target === to.path) return true

    return { path: target }
  }

  // ===============================
  // 🔐 CEK ROLE
  // ===============================
  const requiredRole = to.matched.find(r => r.meta.role)?.meta.role

  if (requiredRole && requiredRole !== auth.user?.role) {
    const target = redirectByRole(auth.user?.role)

    if (target === to.path) return true

    return { path: target }
  }

  return true
})

function redirectByRole(role) {
  if (role === 'admin') return '/admin/dashboard'
  if (role === 'customer') return '/customer/products'
  if (role === 'courier') return '/courier/dashboard'
  return '/login'
}

export default router