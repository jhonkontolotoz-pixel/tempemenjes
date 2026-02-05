<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="text-center mb-10">
            <h1 class="text-dark mb-3">Reset Password</h1>
            <div class="text-gray-400 fw-semibold fs-6">
                Masukkan password baru untuk akun kamu.
            </div>
        </div>

        <form @submit.prevent="submit" class="form w-100">

            <!-- Email -->
            <div class="fv-row mb-8">
                <label class="form-label fw-bold text-dark fs-6">
                    Email
                </label>

                <input
                    type="email"
                    v-model="form.email"
                    class="form-control form-control-lg form-control-solid"
                    readonly
                    autocomplete="username"
                />

                <div v-if="form.errors.email" class="text-danger mt-2">
                    {{ form.errors.email }}
                </div>
            </div>

            <!-- Password -->
            <div class="fv-row mb-8">
                <label class="form-label fw-bold text-dark fs-6">
                    Password Baru
                </label>

                <input
                    type="password"
                    v-model="form.password"
                    class="form-control form-control-lg form-control-lg"
                    required
                    autocomplete="new-password"
                />

                <div v-if="form.errors.password" class="text-danger mt-2">
                    {{ form.errors.password }}
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="fv-row mb-8">
                <label class="form-label fw-bold text-dark fs-6">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    v-model="form.password_confirmation"
                    class="form-control form-control-lg form-control-solid"
                    required
                    autocomplete="new-password"
                />

                <div v-if="form.errors.password_confirmation" class="text-danger mt-2">
                    {{ form.errors.password_confirmation }}
                </div>
            </div>

            <!-- Button -->
            <div class="d-grid">
                <button
                    type="submit"
                    class="btn btn-primary btn-lg"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Reset Password</span>
                    <span v-else>Processing...</span>
                </button>
            </div>

        </form>
    </GuestLayout>
</template>