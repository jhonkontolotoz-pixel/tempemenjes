<script setup>
/**
 * POS.vue - Point of Sale Page
 * 
 * Halaman POS yang hanya bisa diakses oleh Kasir dan Admin.
 * Demonstrasi role-based access untuk halaman spesifik.
 * 
 * Access Control:
 * - Admin: Full access
 * - Kasir: Full access
 * - Manager: No access (redirect)
 * - User: No access (redirect)
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { useRole } from '@/Composables/useRole'
import { ref, computed, onMounted } from 'vue'
import Swal from 'sweetalert2'

const { canAccess, user, isAdmin } = useRole()

// Redirect if not authorized
onMounted(() => {
    if (!canAccess(['admin', 'kasir'])) {
        Swal.fire({
            title: 'Access Denied',
            text: 'You do not have permission to access POS system',
            icon: 'error',
            background: 'var(--bs-body-bg)',
            color: 'var(--bs-body-color)',
        }).then(() => {
            router.visit('/')
        })
    }
})

// Cart items
const cartItems = ref([])

// Available products (simplified)
const products = ref([
    { id: 1, name: 'Laptop ASUS ROG', price: 15000000, stock: 25 },
    { id: 2, name: 'Mouse Logitech MX', price: 850000, stock: 50 },
    { id: 3, name: 'Keyboard Mechanical', price: 1200000, stock: 5 },
    { id: 4, name: 'Monitor LG 27"', price: 3500000, stock: 15 },
])

// Customer info
const customer = ref({
    name: '',
    phone: '',
})

// Add to cart
const addToCart = (product) => {
    const existingItem = cartItems.value.find(item => item.id === product.id)
    
    if (existingItem) {
        if (existingItem.quantity < product.stock) {
            existingItem.quantity++
        } else {
            Swal.fire({
                title: 'Stock Limit',
                text: 'Cannot add more than available stock',
                icon: 'warning',
                timer: 1500,
                background: 'var(--bs-body-bg)',
                color: 'var(--bs-body-color)',
            })
        }
    } else {
        cartItems.value.push({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: 1,
            stock: product.stock,
        })
    }
}

// Remove from cart
const removeFromCart = (index) => {
    cartItems.value.splice(index, 1)
}

// Update quantity
const updateQuantity = (index, change) => {
    const item = cartItems.value[index]
    const newQuantity = item.quantity + change
    
    if (newQuantity > 0 && newQuantity <= item.stock) {
        item.quantity = newQuantity
    }
}

// Calculate totals
const subtotal = computed(() => {
    return cartItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const tax = computed(() => subtotal.value * 0.11) // 11% PPN
const total = computed(() => subtotal.value + tax.value)

// Format currency
const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value)
}

// Process payment
const processPayment = () => {
    if (cartItems.value.length === 0) {
        Swal.fire({
            title: 'Cart Empty',
            text: 'Please add items to cart before checkout',
            icon: 'warning',
            background: 'var(--bs-body-bg)',
            color: 'var(--bs-body-color)',
        })
        return
    }

    Swal.fire({
        title: 'Process Payment?',
        html: `
            <div class="text-start">
                <p><strong>Total:</strong> ${formatCurrency(total.value)}</p>
                <p><strong>Items:</strong> ${cartItems.value.length}</p>
                ${customer.value.name ? `<p><strong>Customer:</strong> ${customer.value.name}</p>` : ''}
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Process Payment',
        cancelButtonText: 'Cancel',
        background: 'var(--bs-body-bg)',
        color: 'var(--bs-body-color)',
    }).then((result) => {
        if (result.isConfirmed) {
            // router.post(route('pos.checkout'), { cartItems, customer, total })
            
            Swal.fire({
                title: 'Success!',
                text: 'Payment processed successfully',
                icon: 'success',
                timer: 2000,
                background: 'var(--bs-body-bg)',
                color: 'var(--bs-body-color)',
            })
            
            // Clear cart
            cartItems.value = []
            customer.value = { name: '', phone: '' }
        }
    })
}

// Clear cart
const clearCart = () => {
    Swal.fire({
        title: 'Clear Cart?',
        text: 'All items will be removed',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Clear',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#f1416c',
        background: 'var(--bs-body-bg)',
        color: 'var(--bs-body-color)',
    }).then((result) => {
        if (result.isConfirmed) {
            cartItems.value = []
        }
    })
}
</script>

<template>
    <Head title="Point of Sale" />

    <AuthenticatedLayout>
        <div class="container-fluid" v-if="canAccess(['admin', 'kasir'])">
            
            <!-- PAGE HEADER -->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <h3 class="fw-bold my-2">
                    <i class="ki-outline ki-calculator fs-2 me-2"></i>
                    Point of Sale
                    <span class="fs-6 text-gray-500 fw-semibold ms-1">
                        Process customer transactions
                    </span>
                </h3>

                <!-- Kasir/Admin Badge -->
                <div class="my-2">
                    <span class="badge badge-lg" 
                          :class="isAdmin ? 'badge-light-danger' : 'badge-light-success'">
                        <i class="ki-outline ki-shield-tick fs-3"></i>
                        {{ isAdmin ? 'Admin' : 'Kasir' }} Mode
                    </span>
                </div>
            </div>

            <div class="row g-6">
                <!-- LEFT SIDE - Products -->
                <div class="col-lg-8">
                    <!-- Customer Info Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h3 class="card-title">Customer Information (Optional)</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <input v-model="customer.name" 
                                           type="text" 
                                           class="form-control" 
                                           placeholder="Enter customer name" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input v-model="customer.phone" 
                                           type="text" 
                                           class="form-control" 
                                           placeholder="Enter phone number" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Select Products</h3>
                            <div class="card-toolbar">
                                <input type="text" 
                                       class="form-control form-control-sm w-200px" 
                                       placeholder="Search products..." />
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div v-for="product in products" 
                                     :key="product.id" 
                                     class="col-md-6 col-xl-4">
                                    <div class="card card-flush h-100 border border-gray-300 hover-elevate-up">
                                        <div class="card-body text-center pt-5">
                                            <div class="symbol symbol-75px mb-4">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-outline ki-shop fs-2x text-primary"></i>
                                                </span>
                                            </div>
                                            <h4 class="fw-bold text-gray-800 mb-2">{{ product.name }}</h4>
                                            <div class="fw-bold text-primary fs-2 mb-3">
                                                {{ formatCurrency(product.price) }}
                                            </div>
                                            <div class="text-muted fs-7 mb-4">
                                                Stock: {{ product.stock }} units
                                            </div>
                                            <button @click="addToCart(product)" 
                                                    class="btn btn-sm btn-primary w-100"
                                                    :disabled="product.stock === 0">
                                                <i class="ki-outline ki-plus fs-2"></i>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE - Cart -->
                <div class="col-lg-4">
                    <div class="card card-sticky" style="position: sticky; top: 20px;">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">
                                <i class="ki-outline ki-shopping-cart fs-2 me-2"></i>
                                Shopping Cart
                            </h3>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                            <!-- Empty Cart -->
                            <div v-if="cartItems.length === 0" class="text-center py-10">
                                <i class="ki-outline ki-shopping-cart fs-5x text-gray-400 mb-4"></i>
                                <p class="text-gray-600">Cart is empty</p>
                            </div>

                            <!-- Cart Items -->
                            <div v-else>
                                <div v-for="(item, index) in cartItems" 
                                     :key="index" 
                                     class="d-flex align-items-center mb-5 pb-5 border-bottom border-gray-300">
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-gray-800 mb-1">{{ item.name }}</div>
                                        <div class="text-muted fs-7">
                                            {{ formatCurrency(item.price) }}
                                        </div>
                                        
                                        <!-- Quantity Controls -->
                                        <div class="d-flex align-items-center mt-2">
                                            <button @click="updateQuantity(index, -1)" 
                                                    class="btn btn-icon btn-sm btn-light-primary">
                                                <i class="ki-outline ki-minus fs-4"></i>
                                            </button>
                                            <span class="mx-3 fw-bold">{{ item.quantity }}</span>
                                            <button @click="updateQuantity(index, 1)" 
                                                    class="btn btn-icon btn-sm btn-light-primary">
                                                <i class="ki-outline ki-plus fs-4"></i>
                                            </button>
                                            <button @click="removeFromCart(index)" 
                                                    class="btn btn-icon btn-sm btn-light-danger ms-auto">
                                                <i class="ki-outline ki-trash fs-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        <div class="fw-bold text-gray-800">
                                            {{ formatCurrency(item.price * item.quantity) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cart Footer -->
                        <div v-if="cartItems.length > 0" class="card-footer">
                            <!-- Totals -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span class="fw-bold text-gray-800">{{ formatCurrency(subtotal) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-gray-600">Tax (11%):</span>
                                    <span class="fw-bold text-gray-800">{{ formatCurrency(tax) }}</span>
                                </div>
                                <div class="separator my-3"></div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-gray-800 fw-bold fs-4">Total:</span>
                                    <span class="text-primary fw-bold fs-2">{{ formatCurrency(total) }}</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column gap-2">
                                <button @click="processPayment" 
                                        class="btn btn-success btn-lg w-100">
                                    <i class="ki-outline ki-check fs-2"></i>
                                    Process Payment
                                </button>
                                <button @click="clearCart" 
                                        class="btn btn-light-danger btn-sm w-100">
                                    <i class="ki-outline ki-trash fs-4"></i>
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card mt-6 bg-light-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="ki-outline ki-chart-simple fs-3x text-info me-4"></i>
                                <div>
                                    <div class="text-gray-600 fs-7">Today's Transactions</div>
                                    <div class="fw-bold fs-2 text-gray-800">45</div>
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
/* Card hover effect */
.hover-elevate-up {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-elevate-up:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
}

/* Scrollbar styling */
.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}

/* Button animations */
.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: scale(1.05);
}

/* Separator */
.separator {
    height: 1px;
    background-color: #e4e6ef;
}
</style>