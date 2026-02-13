<script setup>
/**
 * Products.vue - Products Management Page
 * 
 * Contoh halaman dengan conditional rendering berdasarkan role.
 * Menampilkan:
 * - Admin/Manager: Full CRUD access (Create, Read, Update, Delete)
 * - Kasir: Read-only access
 * - User: No access (redirected)
 * 
 * Features demonstrated:
 * - Role-based button visibility
 * - Conditional action columns
 * - Permission-based UI elements
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { useRole } from '@/Composables/useRole'
import { ref } from 'vue'
import Swal from 'sweetalert2'

const { isAdmin, isManager, canAccess } = useRole()

// Products data (dummy)
const products = ref([
    {
        id: 1,
        name: 'Laptop ASUS ROG',
        sku: 'LAP-001',
        category: 'Electronics',
        price: 15000000,
        stock: 25,
        status: 'active'
    },
    {
        id: 2,
        name: 'Mouse Logitech MX',
        sku: 'MOU-002',
        category: 'Accessories',
        price: 850000,
        stock: 50,
        status: 'active'
    },
    {
        id: 3,
        name: 'Keyboard Mechanical',
        sku: 'KEY-003',
        category: 'Accessories',
        price: 1200000,
        stock: 5,
        status: 'low_stock'
    },
])

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

// Handle delete (Admin/Manager only)
const handleDelete = (productId, productName) => {
    Swal.fire({
        title: 'Hapus Produk?',
        text: `Yakin ingin menghapus "${productName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f1416c',
        background: 'var(--bs-body-bg)',
        color: 'var(--bs-body-color)',
    }).then((result) => {
        if (result.isConfirmed) {
            // router.delete(route('products.destroy', productId))
            Swal.fire({
                title: 'Berhasil!',
                text: 'Produk berhasil dihapus',
                icon: 'success',
                timer: 1500,
                background: 'var(--bs-body-bg)',
                color: 'var(--bs-body-color)',
            })
        }
    })
}

// Get stock badge color
const getStockBadge = (status) => {
    const badges = {
        active: { color: 'success', text: 'In Stock' },
        low_stock: { color: 'warning', text: 'Low Stock' },
        out_of_stock: { color: 'danger', text: 'Out of Stock' },
    }
    return badges[status] || badges.active
}
</script>

<template>
    <Head title="Products Management" />

    <AuthenticatedLayout>
        <div class="container-fluid">
            
            <!-- PAGE HEADER -->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <h3 class="fw-bold my-2">
                    Products Management
                    <span class="fs-6 text-gray-500 fw-semibold ms-1">
                        Manage your product inventory
                    </span>
                </h3>

                <!-- ADD PRODUCT BUTTON - Only for Admin/Manager -->
                <div class="d-flex my-2" v-if="canAccess(['admin', 'manager'])">
                    <Link href="/products/create" class="btn btn-primary">
                        <i class="ki-outline ki-plus fs-2"></i>
                        Add New Product
                    </Link>
                </div>
            </div>

            <!-- ACCESS RESTRICTION NOTICE for Kasir -->
            <div v-if="!canAccess(['admin', 'manager'])" class="alert alert-warning d-flex align-items-center mb-6">
                <i class="ki-outline ki-information-5 fs-2x text-warning me-4"></i>
                <div class="d-flex flex-column">
                    <h4 class="mb-1 text-warning">Read-Only Access</h4>
                    <span>You can view products but cannot add, edit, or delete them.</span>
                </div>
            </div>

            <!-- PRODUCTS TABLE -->
            <div class="card">
                <!-- Card Header -->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <!-- Search -->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" 
                                   class="form-control form-control-solid w-250px ps-13" 
                                   placeholder="Search products..." />
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <!-- Filter Dropdown -->
                        <div class="me-4">
                            <select class="form-select form-select-solid">
                                <option value="">All Categories</option>
                                <option value="electronics">Electronics</option>
                                <option value="accessories">Accessories</option>
                            </select>
                        </div>

                        <!-- Export Button - Only for Admin/Manager -->
                        <button v-if="canAccess(['admin', 'manager'])" 
                                class="btn btn-light-primary">
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
                                    <th class="min-w-125px">Product</th>
                                    <th class="min-w-100px">SKU</th>
                                    <th class="min-w-100px">Category</th>
                                    <th class="min-w-100px">Price</th>
                                    <th class="min-w-100px">Stock</th>
                                    <th class="min-w-100px">Status</th>
                                    <!-- Actions column only for Admin/Manager -->
                                    <th v-if="canAccess(['admin', 'manager'])" 
                                        class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                <tr v-for="product in products" :key="product.id">
                                    <!-- Product Name -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-3">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-shop fs-2x text-primary"></i>
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">
                                                    {{ product.name }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- SKU -->
                                    <td>
                                        <span class="badge badge-light">{{ product.sku }}</span>
                                    </td>

                                    <!-- Category -->
                                    <td>{{ product.category }}</td>

                                    <!-- Price -->
                                    <td class="fw-bold">{{ formatCurrency(product.price) }}</td>

                                    <!-- Stock -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2">{{ product.stock }}</span>
                                            <span class="text-muted fs-7">units</span>
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <span class="badge" 
                                              :class="`badge-light-${getStockBadge(product.status).color}`">
                                            {{ getStockBadge(product.status).text }}
                                        </span>
                                    </td>

                                    <!-- Actions - Only for Admin/Manager -->
                                    <td v-if="canAccess(['admin', 'manager'])" class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- View Button -->
                                            <Link :href="`/products/${product.id}`" 
                                                  class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                <i class="ki-outline ki-eye fs-2"></i>
                                            </Link>

                                            <!-- Edit Button - Only Admin/Manager -->
                                            <Link :href="`/products/${product.id}/edit`" 
                                                  class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                <i class="ki-outline ki-pencil fs-2"></i>
                                            </Link>

                                            <!-- Delete Button - Only Admin -->
                                            <button v-if="isAdmin" 
                                                    @click="handleDelete(product.id, product.name)"
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
                            Showing 1 to {{ products.length }} of {{ products.length }} entries
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-light disabled">Previous</button>
                            <button class="btn btn-sm btn-light active">1</button>
                            <button class="btn btn-sm btn-light disabled">Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ROLE-SPECIFIC INFO CARDS -->
            <div class="row g-6 mt-6">
                <!-- Admin Only Card -->
                <div v-if="isAdmin" class="col-md-4">
                    <div class="card bg-light-danger">
                        <div class="card-body">
                            <i class="ki-outline ki-shield-tick fs-3x text-danger mb-3"></i>
                            <h4 class="fw-bold text-danger">Admin Privileges</h4>
                            <p class="text-gray-600 mb-0">
                                You have full access to create, edit, and delete products.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Manager Only Card -->
                <div v-if="isManager" class="col-md-4">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <i class="ki-outline ki-user-tick fs-3x text-primary mb-3"></i>
                            <h4 class="fw-bold text-primary">Manager Access</h4>
                            <p class="text-gray-600 mb-0">
                                You can create and edit products, but cannot delete them.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Stock Alert - All Roles -->
                <div class="col-md-4">
                    <div class="card bg-light-warning">
                        <div class="card-body">
                            <i class="ki-outline ki-notification-bing fs-3x text-warning mb-3"></i>
                            <h4 class="fw-bold text-warning">Low Stock Alert</h4>
                            <p class="text-gray-600 mb-0">
                                1 product is running low on stock. 
                                <a v-if="canAccess(['admin', 'manager'])" href="#" class="fw-bold">Restock now</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom table styling */
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}

/* Button hover effects */
.btn-icon:hover {
    transform: scale(1.1);
    transition: all 0.2s ease;
}

/* Card hover */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
}

/* Active pagination button */
.btn.active {
    background-color: var(--bs-primary);
    color: white;
}
</style>