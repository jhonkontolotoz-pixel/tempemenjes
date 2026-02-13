<script setup>
/**
 * Customers.vue - Customer Management Page
 * 
 * Menampilkan dan mengelola daftar customer.
 * Features:
 * - Admin/Manager: Full CRUD access
 * - Search by name, email, phone
 * - Filter by status (active/inactive)
 * - Customer statistics
 * - Bulk actions (if needed)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useRole } from '@/Composables/useRole'
import { ref, computed } from 'vue'
import Swal from 'sweetalert2'

const { isAdmin, isManager, canAccess } = useRole()

// Props from controller
const props = defineProps({
    customers: Object,
    filters: Object,
    stats: Object,
})

// Local state
const searchQuery = ref(props.filters?.search || '')
const statusFilter = ref(props.filters?.status || '')

// Format date
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}

// Get status badge
const getStatusBadge = (status) => {
    const badges = {
        active: { color: 'success', text: 'Active' },
        inactive: { color: 'danger', text: 'Inactive' },
    }
    return badges[status] || badges.inactive
}

// Handle search
const handleSearch = () => {
    router.get(route('customers.index'), {
        search: searchQuery.value,
        status: statusFilter.value,
    })
}

// Handle reset filters
const handleResetFilters = () => {
    searchQuery.value = ''
    statusFilter.value = ''
    router.get(route('customers.index'))
}

// Handle delete
const handleDelete = (customerId, customerName) => {
    Swal.fire({
        title: 'Hapus Customer?',
        text: `Yakin ingin menghapus "${customerName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f1416c',
        background: 'var(--bs-body-bg)',
        color: 'var(--bs-body-color)',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('customers.destroy', customerId), {
                onSuccess: () => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Customer berhasil dihapus',
                        icon: 'success',
                        timer: 1500,
                        background: 'var(--bs-body-bg)',
                        color: 'var(--bs-body-color)',
                    })
                },
                onError: () => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal menghapus customer',
                        icon: 'error',
                        background: 'var(--bs-body-bg)',
                        color: 'var(--bs-body-color)',
                    })
                }
            })
        }
    })
}

// Handle edit
const handleEdit = (customerId) => {
    router.get(route('customers.edit', customerId))
}
</script>

<template>
    <Head title="Customers Management" />

    <AuthenticatedLayout>
        <div class="container-fluid">
            
            <!-- PAGE HEADER -->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <h3 class="fw-bold my-2">
                    <i class="ki-outline ki-user fs-2 me-2"></i>
                    Customers Management
                    <span class="fs-6 text-gray-500 fw-semibold ms-1">
                        Manage your customer database
                    </span>
                </h3>

                <!-- ADD CUSTOMER BUTTON - Only for Admin/Manager -->
                <div class="d-flex my-2" v-if="canAccess(['admin', 'manager'])">
                    <button class="btn btn-primary" @click="router.get(route('customers.create'))">
                        <i class="ki-outline ki-plus fs-2"></i>
                        Add New Customer
                    </button>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="row g-6 mb-6">
                <!-- Total Customers -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-user fs-2x text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Total Customers</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ stats?.total || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Customers -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-check-circle fs-2x text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Active</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ stats?.active || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inactive Customers -->
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60px me-4">
                                    <span class="symbol-label bg-light-danger">
                                        <i class="ki-outline ki-cross-circle fs-2x text-danger"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-500 fw-semibold d-block fs-7">Inactive</span>
                                    <span class="text-gray-800 fw-bold fs-2">{{ stats?.inactive || 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CUSTOMERS TABLE -->
            <div class="card">
                <!-- Card Header -->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <!-- Search -->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input v-model="searchQuery"
                                   @keyup.enter="handleSearch"
                                   type="text" 
                                   class="form-control form-control-solid w-250px ps-13" 
                                   placeholder="Search by name, email, phone..." />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <!-- Status Filter -->
                        <div class="me-4">
                            <select v-model="statusFilter" @change="handleSearch" class="form-select form-select-solid">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Reset Button -->
                        <button @click="handleResetFilters" class="btn btn-light-secondary me-3">
                            <i class="ki-outline ki-reload fs-2"></i>
                        </button>

                        <!-- Export Button -->
                        <button class="btn btn-light-primary">
                            <i class="ki-outline ki-exit-up fs-2"></i>
                            Export
                        </button>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">Customer</th>
                                    <th class="min-w-150px">Email</th>
                                    <th class="min-w-120px">Phone</th>
                                    <th class="min-w-150px">Address</th>
                                    <th class="min-w-100px">City</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-100px">Joined</th>
                                    <th v-if="canAccess(['admin', 'manager'])" class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <tr v-for="customer in customers.data" :key="customer.id">
                                    <!-- Customer Name -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-3">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-user fs-2x text-primary"></i>
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-gray-800 fw-bold">{{ customer.name }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td>
                                        <span v-if="customer.email" class="text-gray-600">{{ customer.email }}</span>
                                        <span v-else class="text-muted fs-7">-</span>
                                    </td>

                                    <!-- Phone -->
                                    <td>
                                        <span v-if="customer.phone" class="badge badge-light">{{ customer.phone }}</span>
                                        <span v-else class="text-muted fs-7">-</span>
                                    </td>

                                    <!-- Address -->
                                    <td>
                                        <span v-if="customer.address" class="text-gray-600 fs-7">{{ customer.address }}</span>
                                        <span v-else class="text-muted fs-7">-</span>
                                    </td>

                                    <!-- City -->
                                    <td>
                                        <span v-if="customer.city" class="text-gray-600">{{ customer.city }}</span>
                                        <span v-else class="text-muted fs-7">-</span>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <span class="badge" :class="`badge-light-${getStatusBadge(customer.status).color}`">
                                            {{ getStatusBadge(customer.status).text }}
                                        </span>
                                    </td>

                                    <!-- Joined Date -->
                                    <td>
                                        <span class="text-gray-600 fs-7">{{ formatDate(customer.created_at) }}</span>
                                    </td>

                                    <!-- Actions -->
                                    <td v-if="canAccess(['admin', 'manager'])" class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- View Button -->
                                            <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                <i class="ki-outline ki-eye fs-2"></i>
                                            </button>

                                            <!-- Edit Button -->
                                            <button @click="handleEdit(customer.id)"
                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                <i class="ki-outline ki-pencil fs-2"></i>
                                            </button>

                                            <!-- Delete Button - Only Admin -->
                                            <button v-if="isAdmin"
                                                    @click="handleDelete(customer.id, customer.name)"
                                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                                <i class="ki-outline ki-trash fs-2"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center flex-wrap pt-5">
                        <div class="text-gray-600">
                            Showing {{ customers.from || 0 }} to {{ customers.to || 0 }} of {{ customers.total || 0 }} customers
                        </div>
                        <div class="d-flex gap-2">
                            <button v-if="customers.prev_page_url" 
                                    @click="router.get(customers.prev_page_url)"
                                    class="btn btn-sm btn-light">Previous</button>
                            <button v-else class="btn btn-sm btn-light disabled">Previous</button>

                            <div class="d-flex gap-1">
                                <button v-for="page in customers.last_page" :key="page"
                                        @click="router.get(route('customers.index', { page }))"
                                        :class="{ 'active': page === customers.current_page }"
                                        class="btn btn-sm btn-light">
                                    {{ page }}
                                </button>
                            </div>

                            <button v-if="customers.next_page_url"
                                    @click="router.get(customers.next_page_url)"
                                    class="btn btn-sm btn-light">Next</button>
                            <button v-else class="btn btn-sm btn-light disabled">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>
