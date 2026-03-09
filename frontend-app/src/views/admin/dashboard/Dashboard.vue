<template>
  <div class="space-y-8">

    <!-- HEADER + FILTER -->
    <div class="flex justify-between items-center">
      <div>
        <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
        <p class="text-gray-500 text-sm">Monitoring keseluruhan sistem</p>
      </div>

      <select v-model="range"
              @change="loadDashboard"
              class="border rounded-lg px-3 py-2 text-sm shadow-sm">
        <option value="7">Last 7 Days</option>
        <option value="30">Last 30 Days</option>
      </select>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-6">
      <div v-for="card in summaryCards"
           :key="card.title"
           class="p-5 rounded-2xl text-white shadow-lg transform hover:scale-105 transition"
           :class="card.color">
        <p class="text-sm opacity-80">{{ card.title }}</p>
        <h3 class="text-2xl font-bold mt-2">{{ card.value }}</h3>
      </div>
    </div>

    <!-- CHART GRID -->
    <div class="grid md:grid-cols-2 gap-6">

      <!-- LINE CHART -->
      <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h3 class="font-semibold mb-4 text-gray-700">Revenue</h3>
        <canvas ref="lineRef"></canvas>
      </div>

      <!-- BAR CHART -->
      <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h3 class="font-semibold mb-4 text-gray-700">Orders</h3>
        <canvas ref="barRef"></canvas>
      </div>

      <!-- DOUGHNUT -->
      <div class="bg-white p-6 rounded-2xl shadow-lg md:col-span-2">
        <h3 class="font-semibold mb-4 text-gray-700">Order Status</h3>
        <div class="max-w-xs mx-auto">
          <canvas ref="doughnutRef"></canvas>
        </div>
      </div>

    </div>

    <!-- LATEST ORDERS -->
    <div class="bg-white p-6 rounded-2xl shadow-lg">
      <h3 class="font-semibold mb-4 text-gray-700">5 Latest Orders</h3>

      <table class="w-full text-sm">
        <thead class="border-b text-gray-600">
          <tr class="text-left">
            <th class="py-2">Order Code</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in latestOrders"
              :key="order.id"
              class="border-b hover:bg-gray-50">
            <td class="py-3 font-medium">{{ order.code }}</td>
            <td>{{ order.customer }}</td>
            <td class="font-semibold text-emerald-600">
              Rp {{ Number(order.total).toLocaleString() }}
            </td>
            <td>
              <span class="px-3 py-1 rounded-full text-xs font-medium"
                    :class="statusClass(order.raw_status)">
                {{ order.status }}
              </span>
            </td>
            <td>{{ order.date }}</td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '@/api/axios'
import Chart from 'chart.js/auto'

const range = ref(7)

const summaryCards = ref([])
const latestOrders = ref([])

const lineRef = ref(null)
const barRef = ref(null)
const doughnutRef = ref(null)

let lineChart, barChart, doughnutChart

function statusClass(status) {
  if (status === 'WAITING_PAYMENT')
    return 'bg-yellow-100 text-yellow-700'
  if (status === 'DELIVERED')
    return 'bg-green-100 text-green-700'
  if (status === 'ASSIGNED')
    return 'bg-blue-100 text-blue-700'
  if (status === 'CANCELLED')
    return 'bg-red-100 text-red-700'
  return 'bg-gray-100 text-gray-700'
}

async function loadDashboard() {
  const res = await axios.get(`/admin/dashboard?range=${range.value}`)
  const data = res.data.data

  // SUMMARY
  summaryCards.value = [
    { title: 'Users', value: data.summary.total_users, color: 'bg-gradient-to-r from-blue-500 to-blue-600' },
    { title: 'Products', value: data.summary.total_products, color: 'bg-gradient-to-r from-purple-500 to-purple-600' },
    { title: 'Orders', value: data.summary.total_orders, color: 'bg-gradient-to-r from-indigo-500 to-indigo-600' },
    { title: 'Revenue', value: 'Rp ' + Number(data.summary.revenue).toLocaleString(), color: 'bg-gradient-to-r from-emerald-500 to-emerald-600' },
    { title: 'Pending', value: data.summary.pending_orders, color: 'bg-gradient-to-r from-yellow-500 to-yellow-600' },
    { title: 'Delivered', value: data.summary.delivered_orders, color: 'bg-gradient-to-r from-green-500 to-green-600' },
  ]

  latestOrders.value = data.latest_orders || []

  renderLine(data.revenue_chart || [])
  renderBar(data.orders_chart || [])
  renderDoughnut(data.status_chart || {})
}

function renderLine(chartData) {
  if (lineChart) lineChart.destroy()

  lineChart = new Chart(lineRef.value, {
    type: 'line',
    data: {
      labels: chartData.map(i => i.date),
      datasets: [{
        label: 'Revenue',
        data: chartData.map(i => i.total),
        borderColor: '#10B981',
        backgroundColor: 'rgba(16,185,129,0.2)',
        fill: true,
        tension: 0.4
      }]
    },
    options: { responsive: true }
  })
}

function renderBar(chartData) {
  if (barChart) barChart.destroy()

  barChart = new Chart(barRef.value, {
    type: 'bar',
    data: {
      labels: chartData.map(i => i.date),
      datasets: [{
        label: 'Orders',
        data: chartData.map(i => i.total),
        backgroundColor: '#6366F1'
      }]
    },
    options: { responsive: true }
  })
}

function renderDoughnut(statusData) {
  if (doughnutChart) doughnutChart.destroy()

  doughnutChart = new Chart(doughnutRef.value, {
    type: 'doughnut',
    data: {
      labels: ['Pending', 'Delivered', 'Cancelled'],
      datasets: [{
        data: [
          statusData.pending || 0,
          statusData.delivered || 0,
          statusData.cancelled || 0
        ],
        backgroundColor: [
          '#F59E0B',
          '#10B981',
          '#EF4444'
        ]
      }]
    },
    options: { responsive: true }
  })
}

onMounted(loadDashboard)
</script>