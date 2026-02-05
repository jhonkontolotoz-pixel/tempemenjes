<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
    status: String,
})

const form = useForm({
    email: '',
})

const submit = () => {
    form.post(route('password.email'))
}
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="text-center mb-10">
            <h1 class="text-dark mb-3">Forgot Password</h1>
            <div class="text-gray-400 fw-semibold fs-6">
                Masukkan email akun kamu untuk menerima link reset password.
            </div>
        </div>

        <div v-if="status" class="alert alert-success mb-5">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="form w-100">

            <div class="fv-row mb-8">
                <label class="form-label fw-bold text-dark fs-6">
                    Email
                </label>

                <input
                    type="email"
                    v-model="form.email"
                    class="form-control form-control-lg form-control-lg"
                    required
                    autocomplete="username"
                />

                <div v-if="form.errors.email" class="text-danger mt-2">
                    {{ form.errors.email }}
                </div>
            </div>

            <div class="d-grid">
                <button
                    type="submit"
                    class="btn btn-primary btn-lg"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Kirim Link Reset</span>
                    <span v-else>Processing...</span>
                </button>
            </div>

        </form>
    </GuestLayout>
</template>