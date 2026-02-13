<script setup>
/**
 * Dashboard.vue - Role-Aware Dashboard
 * 
 * Dashboard yang menampilkan konten berbeda berdasarkan role user:
 * - Admin: Full statistics, user management, system overview
 * - Manager: Sales charts, product stock, reports overview
 * - Kasir: POS quick access, today's transactions
 * - User: Welcome message, profile info
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { useRole } from '@/Composables/useRole'
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const { user, isAdmin, isManager, isKasir, isUser } = useRole()

// =========================
// ADMIN STATISTICS
// =========================
const adminStats = ref({
    totalUsers: 156,
    totalCustomers: 1243,
    totalProducts: 567,
    totalSales: 'Rp 45.8M',
})

// =========================
// MANAGER STATISTICS
// =========================
const managerStats = ref({
    todaySales: 'Rp 2.4M',
    lowStock: 23,
    pendingOrders: 15,
    topProduct: 'Laptop ASUS ROG',
})

// =========================
// KASIR STATISTICS
// =========================
const kasirStats = ref({
    todayTransactions: 45,
    todayRevenue: 'Rp 1.2M',
    cashInHand: 'Rp 5.5M',
})

// =========================
// RECENT ACTIVITIES (ALL ROLES)
// =========================
const recentActivities = ref([
    {
        id: 1,
        title: 'New customer registered',
        type: 'customer',
        time: '5 minutes ago',
        icon: 'ki-user',
        color: 'primary'
    },
    {
        id: 2,
        title: 'Product stock updated',
        type: 'product',
        time: '1 hour ago',
        icon: 'ki-shop',
        color: 'success'
    },
    {
        id: 3,
        title: 'Invoice #INV-2024-001 created',
        type: 'invoice',
        time: '2 hours ago',
        icon: 'ki-document',
        color: 'warning'
    },
])

// Dashboard title based on role
const dashboardTitle = computed(() => {
    if (isAdmin.value) return 'Admin Dashboard'
    if (isManager.value) return 'Manager Dashboard'
    if (isKasir.value) return 'Kasir Dashboard'
    return 'Dashboard'
})

// Greeting based on time
const greeting = computed(() => {
    const hour = new Date().getHours()
    if (hour < 12) return 'Selamat Pagi'
    if (hour < 15) return 'Selamat Siang'
    if (hour < 18) return 'Selamat Sore'
    return 'Selamat Malam'
})
</script>

<template>
    <Head :title="dashboardTitle" />

    <AuthenticatedLayout>
        <div class="container-fluid">
            
            <!-- GREETING HEADER (ALL ROLES) -->
            <div class="card mb-6 mb-xl-8 bg-gradient-primary">
                <div class="card-body py-6">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px me-4">
                            <img :src="user?.avatar || '/media/avatars/rud.jpg'" alt="avatar" />
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="text-white fw-bold mb-1">
                                {{ greeting }}, {{ user?.name }}!
                            </h3>
                            <p class="text-white opacity-75 mb-0">
                                Welcome to your {{ user?.role?.name || 'User' }} dashboard
                            </p>
                        </div>
                        <div class="ms-auto">
                            <span class="badge badge-lg badge-light-success">
                                <i class="ki-outline ki-check-circle fs-3 me-1"></i>
                                Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADMIN DASHBOARD -->
            <template v-if="isAdmin">
                <div class="row g-6 g-xl-9 mb-6">
                    <!-- Total Users -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-people fs-2x text-primary"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Total Users</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ adminStats.totalUsers }}</span>
                                    </div>
                                </div>
                                <Link href="/users" class="btn btn-sm btn-light-primary w-100">
                                    Manage Users
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Total Customers -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-user fs-2x text-success"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Total Customers</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ adminStats.totalCustomers }}</span>
                                    </div>
                                </div>
                                <Link href="/customers" class="btn btn-sm btn-light-success w-100">
                                    View Customers
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-warning">
                                            <i class="ki-outline ki-shop fs-2x text-warning"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Total Products</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ adminStats.totalProducts }}</span>
                                    </div>
                                </div>
                                <Link href="/products" class="btn btn-sm btn-light-warning w-100">
                                    Manage Products
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-danger">
                                            <i class="ki-outline ki-chart fs-2x text-danger"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Total Sales</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ adminStats.totalSales }}</span>
                                    </div>
                                </div>
                                <Link href="/reports" class="btn btn-sm btn-light-danger w-100">
                                    View Reports
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions for Admin -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <Link href="/customers/create" class="btn btn-primary">
                                <i class="ki-outline ki-plus fs-2"></i>
                                Add Customer
                            </Link>
                            <Link href="/products/create" class="btn btn-success">
                                <i class="ki-outline ki-plus fs-2"></i>
                                Add Product
                            </Link>
                            <Link href="/pos" class="btn btn-warning">
                                <i class="ki-outline ki-calculator fs-2"></i>
                                Open POS
                            </Link>
                            <Link href="/reports" class="btn btn-info">
                                <i class="ki-outline ki-chart fs-2"></i>
                                View Reports
                            </Link>
                        </div>
                    </div>
                </div>
            </template>

            <!-- MANAGER DASHBOARD -->
            <template v-if="isManager">
                <div class="row g-6 g-xl-9 mb-6">
                    <!-- Today's Sales -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-dollar fs-2x text-success"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Today's Sales</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ managerStats.todaySales }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Products -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-warning">
                                            <i class="ki-outline ki-notification-bing fs-2x text-warning"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Low Stock Alert</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ managerStats.lowStock }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Orders -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-package fs-2x text-primary"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Pending Orders</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ managerStats.pendingOrders }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Product -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-danger">
                                            <i class="ki-outline ki-star fs-2x text-danger"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Top Product</span>
                                        <span class="text-gray-800 fw-bold d-block fs-7">{{ managerStats.topProduct }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manager Quick Actions -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="card-title">Manager Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3">
                            <Link href="/customers" class="btn btn-primary">
                                <i class="ki-outline ki-user fs-2"></i>
                                Manage Customers
                            </Link>
                            <Link href="/products" class="btn btn-success">
                                <i class="ki-outline ki-shop fs-2"></i>
                                Manage Products
                            </Link>
                            <Link href="/reports" class="btn btn-info">
                                <i class="ki-outline ki-chart fs-2"></i>
                                View Reports
                            </Link>
                        </div>
                    </div>
                </div>
            </template>

            <!-- KASIR DASHBOARD -->
            <template v-if="isKasir">
                <div class="row g-6 g-xl-9 mb-6">
                    <!-- Today's Transactions -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-calculator fs-2x text-primary"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Today's Transactions</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ kasirStats.todayTransactions }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Revenue -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-dollar fs-2x text-success"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Today's Revenue</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ kasirStats.todayRevenue }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cash in Hand -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <span class="symbol-label bg-light-warning">
                                            <i class="ki-outline ki-wallet fs-2x text-warning"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 fw-semibold d-block fs-7">Cash in Hand</span>
                                        <span class="text-gray-800 fw-bold d-block fs-2x">{{ kasirStats.cashInHand }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POS Quick Access -->
                <div class="card mb-6 bg-light-primary">
                    <div class="card-body text-center py-8">
                        <i class="ki-outline ki-calculator fs-5x text-primary mb-4"></i>
                        <h3 class="fw-bold text-gray-800 mb-3">Ready to Start?</h3>
                        <p class="text-gray-600 mb-6">Buka Point of Sale untuk melayani pelanggan</p>
                        <Link href="/pos" class="btn btn-lg btn-primary">
                            <i class="ki-outline ki-shop fs-2"></i>
                            Buka POS Sekarang
                        </Link>
                    </div>
                </div>
            </template>

            <!-- USER (BASIC) DASHBOARD -->
            <template v-if="isUser">
                <div class="card mb-6">
                    <div class="card-body text-center py-10">
                        <div class="symbol symbol-100px symbol-circle mb-5 mx-auto">
                            <img :src="user?.avatar || '/media/avatars/rud.jpg'" alt="avatar" />
                        </div>
                        <h3 class="fw-bold text-gray-800 mb-2">{{ user?.name }}</h3>
                        <p class="text-gray-600 mb-6">{{ user?.email }}</p>
                        <Link href="/profile" class="btn btn-primary">
                            <i class="ki-outline ki-profile-circle fs-2"></i>
                            View Profile
                        </Link>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Account Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="fw-bold text-gray-600 mb-2">Full Name</label>
                                <div class="text-gray-800">{{ user?.name }}</div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="fw-bold text-gray-600 mb-2">Email</label>
                                <div class="text-gray-800">{{ user?.email }}</div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="fw-bold text-gray-600 mb-2">Role</label>
                                <div>
                                    <span class="badge badge-light-primary">{{ user?.role?.name || 'User' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="fw-bold text-gray-600 mb-2">Status</label>
                                <div>
                                    <span class="badge badge-light-success">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- RECENT ACTIVITIES (ALL ROLES) -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="card-title">Recent Activities</h3>
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light">
                            View All
                        </button>
                    </div>
                </div>
                <div class="card-body py-3">
                    <div class="timeline">
                        <div v-for="activity in recentActivities" 
                             :key="activity.id"
                             class="timeline-item mb-5">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div :class="`symbol-label bg-light-${activity.color}`">
                                    <i :class="`ki-outline ${activity.icon} fs-2 text-${activity.color}`"></i>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-2">
                                    <div class="fs-6 fw-bold text-gray-800">{{ activity.title }}</div>
                                    <div class="d-flex align-items-center mt-1 fs-7">
                                        <div class="text-muted me-2">{{ activity.time }}</div>
                                        <span :class="`badge badge-light-${activity.color}`">{{ activity.type }}</span>
                                    </div>
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
/* Gradient Background for Header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Timeline Styling */
.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    position: relative;
}

.timeline-line {
    border-left: 1px dashed #e4e6ef;
    margin-left: 20px;
}

.timeline-icon {
    position: absolute;
    left: 0;
}

.timeline-content {
    flex: 1;
    padding-left: 2rem;
}

/* Card Hover Effect */
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}
</style>