<script setup>
/**
 * CustomerForm.vue - Customer Create/Edit Form
 * 
 * Form untuk membuat atau mengedit customer.
 */

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import Swal from 'sweetalert2'

// Props
const props = defineProps({
    customer: Object,
})

// Local state
const form = ref({
    name: props.customer?.name || '',
    email: props.customer?.email || '',
    phone: props.customer?.phone || '',
    address: props.customer?.address || '',
    city: props.customer?.city || '',
    postal_code: props.customer?.postal_code || '',
    status: props.customer?.status || 'active',
})

const errors = ref({})
const isSubmitting = ref(false)

// Handle submit
const handleSubmit = () => {
    isSubmitting.value = true
    errors.value = {}

    if (props.customer) {
        // Update customer
        router.patch(route('customers.update', props.customer.id), form.value, {
            onSuccess: () => {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Customer berhasil diupdate',
                    icon: 'success',
                    timer: 2000,
                    background: 'var(--bs-body-bg)',
                    color: 'var(--bs-body-color)',
                }).then(() => {
                    router.get(route('customers.index'))
                })
            },
            onError: (errs) => {
                errors.value = errs
                isSubmitting.value = false
            }
        })
    } else {
        // Create customer
        router.post(route('customers.store'), form.value, {
            onSuccess: () => {
                Swal.fire({
                    title: 'Sukses!',
                    text: 'Customer berhasil ditambahkan',
                    icon: 'success',
                    timer: 2000,
                    background: 'var(--bs-body-bg)',
                    color: 'var(--bs-body-color)',
                }).then(() => {
                    router.get(route('customers.index'))
                })
            },
            onError: (errs) => {
                errors.value = errs
                isSubmitting.value = false
            }
        })
    }
}

// Handle cancel
const handleCancel = () => {
    router.get(route('customers.index'))
}
</script>

<template>
    <Head :title="customer ? 'Edit Customer' : 'Create Customer'" />

    <AuthenticatedLayout>
        <div class="container-fluid">
            
            <!-- PAGE HEADER -->
            <div class="d-flex flex-wrap flex-stack mb-6">
                <h3 class="fw-bold my-2">
                    <i class="ki-outline ki-user fs-2 me-2"></i>
                    {{ customer ? 'Edit Customer' : 'Add New Customer' }}
                    <span class="fs-6 text-gray-500 fw-semibold ms-1">
                        {{ customer ? 'Update customer information' : 'Create a new customer' }}
                    </span>
                </h3>
            </div>

            <!-- FORM CARD -->
            <div class="row g-6">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <form @submit.prevent="handleSubmit">
                                <!-- Name -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Customer Name <span class="text-danger">*</span></label>
                                    <input v-model="form.name" 
                                           type="text" 
                                           class="form-control" 
                                           :class="{ 'is-invalid': errors.name }"
                                           placeholder="Enter customer name" />
                                    <div v-if="errors.name" class="invalid-feedback d-block">
                                        {{ errors.name }}
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input v-model="form.email" 
                                           type="email" 
                                           class="form-control"
                                           :class="{ 'is-invalid': errors.email }"
                                           placeholder="Enter email address" />
                                    <div v-if="errors.email" class="invalid-feedback d-block">
                                        {{ errors.email }}
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input v-model="form.phone" 
                                           type="text" 
                                           class="form-control"
                                           :class="{ 'is-invalid': errors.phone }"
                                           placeholder="Enter phone number" />
                                    <div v-if="errors.phone" class="invalid-feedback d-block">
                                        {{ errors.phone }}
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Address</label>
                                    <textarea v-model="form.address" 
                                              class="form-control"
                                              :class="{ 'is-invalid': errors.address }"
                                              placeholder="Enter address"
                                              rows="3"></textarea>
                                    <div v-if="errors.address" class="invalid-feedback d-block">
                                        {{ errors.address }}
                                    </div>
                                </div>

                                <!-- City & Postal Code -->
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">City</label>
                                        <input v-model="form.city" 
                                               type="text" 
                                               class="form-control"
                                               :class="{ 'is-invalid': errors.city }"
                                               placeholder="Enter city" />
                                        <div v-if="errors.city" class="invalid-feedback d-block">
                                            {{ errors.city }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Postal Code</label>
                                        <input v-model="form.postal_code" 
                                               type="text" 
                                               class="form-control"
                                               :class="{ 'is-invalid': errors.postal_code }"
                                               placeholder="Enter postal code" />
                                        <div v-if="errors.postal_code" class="invalid-feedback d-block">
                                            {{ errors.postal_code }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select v-model="form.status" 
                                            class="form-select"
                                            :class="{ 'is-invalid': errors.status }">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    <div v-if="errors.status" class="invalid-feedback d-block">
                                        {{ errors.status }}
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-3">
                                    <button type="submit" 
                                            :disabled="isSubmitting"
                                            class="btn btn-primary">
                                        <span v-if="!isSubmitting">
                                            <i class="ki-outline ki-check fs-2"></i>
                                            {{ customer ? 'Update Customer' : 'Create Customer' }}
                                        </span>
                                        <span v-else>
                                            <i class="ki-outline ki-loading fs-2"></i>
                                            Processing...
                                        </span>
                                    </button>
                                    <button type="button" 
                                            @click="handleCancel"
                                            class="btn btn-light">
                                        <i class="ki-outline ki-cross fs-2"></i>
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- INFO SIDEBAR -->
                <div class="col-lg-4">
                    <div class="card bg-light-primary mb-6">
                        <div class="card-body">
                            <h4 class="fw-bold text-primary mb-3">Form Instructions</h4>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="ki-outline ki-check-circle text-success me-2"></i>
                                    <span class="text-gray-700">Fill in at least the customer name</span>
                                </li>
                                <li class="mb-2">
                                    <i class="ki-outline ki-check-circle text-success me-2"></i>
                                    <span class="text-gray-700">Email must be unique</span>
                                </li>
                                <li class="mb-2">
                                    <i class="ki-outline ki-check-circle text-success me-2"></i>
                                    <span class="text-gray-700">Phone format: any format is accepted</span>
                                </li>
                                <li>
                                    <i class="ki-outline ki-check-circle text-success me-2"></i>
                                    <span class="text-gray-700">Set status to Active to allow transactions</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Changelog Card (for edit) -->
                    <div v-if="customer" class="card">
                        <div class="card-header">
                            <h3 class="card-title fw-bold">Change History</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <span class="text-gray-600 fw-semibold d-block mb-1">Created Date</span>
                                <span class="text-gray-800">{{ new Date(customer.created_at).toLocaleDateString('id-ID') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 fw-semibold d-block mb-1">Last Updated</span>
                                <span class="text-gray-800">{{ new Date(customer.updated_at).toLocaleDateString('id-ID') }}</span>
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
