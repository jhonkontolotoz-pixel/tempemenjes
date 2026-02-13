<script setup>
/**
 * Reports.vue - Sales Reports Page
 * 
 * Menampilkan laporan penjualan dan analisis bisnis.
 * Features:
 * - Date range filter
 * - Sales statistics (revenue, transactions, customers, average)
 * - Payment methods breakdown
 * - Sales trend chart
 * - Export functionality
 * 
 * Access: Admin & Manager only
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { useRole } from '@/Composables/useRole'
import { ref } from 'vue'

const { isAdmin, isManager, canAccess } = useRole()

// Props from controller
const props = defineProps({
    salesData: Array,
    stats: Object,
    paymentMethods: Array,
    filters: Object,
})

// Local state
const dateFrom = ref(props.filters?.date_from || '')
const dateTo = ref(props.filters?.date_to || '')

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value || 0)
}

// Handle date filter
const handleFilter = () => {
    router.get(route('reports.index'), {
        date_from: dateFrom.value,
        date_to: dateTo.value,
    })
}

// Get payment method badge color
const getPaymentBadgeColor = (method) => {
    const colors = {
        cash: 'success',
        card: 'primary',
        transfer: 'info',
        'e-wallet': 'warning',
        check: 'secondary',
    }
    return colors[method] || 'light'
}
</script>

<template>
    <Head title="Sales Reports" />

    <AuthenticatedLayout>
        <div class="container-fluid">
            
            <!-- PAGE HEADER -->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <h3 class="fw-bold my-2">
                    <i class="ki-outline ki-chart fs-2 me-2"></i>
                    Sales Reports
                    <span class="fs-6 text-gray-500 fw-semibold ms-1">
                        Analyze your sales performance
                    </span>
                </h3>

                <!-- Print/Export -->
                <div class="d-flex my-2 gap-3">
                    <button class="btn btn-light-secondary">
                        <i class="ki-outline ki-printer fs-2"></i>
                        Print
                    </button>
                    <button class="btn btn-light-primary">
                        <i class="ki-outline ki-exit-up fs-2"></i>
                        Export CSV
                    </button>
                </div>
            </div>

            <!-- DATE FILTER -->
            <div class="card mb-6">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">From Date</label>
                            <input v-model="dateFrom" type="date" class="form-control" />
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">To Date</label>
                            <input v-model="dateTo" type="date" class="form-control" />
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button @click="handleFilter" class="btn btn-primary w-100">
                                <i class="ki-outline ki-magnifier fs-2"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KEY STATISTICS -->
            <div class="row g-6 mb-6">
                <!-- Total Revenue -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-dollar fs-2x text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Total Revenue</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ formatCurrency(stats?.total_revenue) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Transactions -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-calculator fs-2x text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Transactions</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ stats?.total_transactions || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-user fs-2x text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Customers</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ stats?.total_customers || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Transaction -->
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-info">
                                        <i class="ki-outline ki-abstract-26 fs-2x text-info"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Avg Transaction</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ formatCurrency(stats?.avg_transaction) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SALES DATA TABLE -->
            <div class="row g-6 mb-6">
                <!-- Daily Sales -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Daily Sales Trend</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-150px">Date</th>
                                            <th class="min-w-100px text-end">Transactions</th>
                                            <th class="min-w-150px text-end">Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        <tr v-for="(sale, index) in salesData" :key="index">
                                            <td>
                                                <span class="text-gray-800 fw-bold">
                                                    {{ new Date(sale.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-light-primary">{{ sale.total_transactions }}</span>
                                            </td>
                                            <td class="text-end fw-bold">
                                                {{ formatCurrency(sale.revenue) }}
                                            </td>
                                        </tr>
                                        <tr v-if="!salesData || salesData.length === 0">
                                            <td colspan="3" class="text-center text-muted py-5">
                                                No sales data available for the selected period
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Payment Methods</h3>
                        </div>
                        <div class="card-body">
                            <div v-for="(method, index) in paymentMethods" :key="index" class="mb-5">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>
                                        <span class="fs-6 fw-bold text-capitalize">{{ method.payment_method }}</span>
                                        <div class="text-gray-600 fs-7">
                                            {{ method.count }} transactions
                                        </div>
                                    </div>
                                    <span class="badge" :class="`badge-light-${getPaymentBadgeColor(method.payment_method)}`">
                                        {{ formatCurrency(method.total) }}
                                    </span>
                                </div>
                                <div class="progress h-5px">
                                    <div class="progress-bar" 
                                         :style="{ width: (method.total / (paymentMethods[0]?.total || 1)) * 100 + '%' }">
                                    </div>
                                </div>
                            </div>
                            <div v-if="!paymentMethods || paymentMethods.length === 0" class="text-center text-muted py-5">
                                No payment data available
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SUMMARY CARD -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title fw-bold">Report Summary</h3>
                </div>
                <div class="card-body">
                    <div class="row g-6">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <i class="ki-outline ki-chart-line-up fs-2x text-success"></i>
                                </div>
                                <div>
                                    <span class="text-gray-600 fw-semibold d-block fs-7">Performance Status</span>
                                    <span class="text-gray-800 fw-bold fs-6">
                                        {{ stats?.total_transactions > 0 ? 'Transactions are active' : 'No transactions yet' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-4">
                                <div class="me-4">
                                    <i class="ki-outline ki-user-circle fs-2x text-primary"></i>
                                </div>
                                <div>
                                    <span class="text-gray-600 fw-semibold d-block fs-7">Customer Base</span>
                                    <span class="text-gray-800 fw-bold fs-6">
                                        {{ stats?.total_customers || 0 }} customers in this period
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
