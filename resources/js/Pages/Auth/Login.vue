<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineProps({
    canResetPassword: Boolean,
    status: String,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="LoginCUY" />

        <!-- Status Message -->
        <div v-if="status" class="mb-10 alert alert-success">
            {{ status }}
        </div>

        <!-- Title -->
        <div class="text-center mb-10">
            <h1 class="text-dark fw-bolder mb-3">
                LOGIN
            </h1>
            <div class="text-gray-500 fw-semibold fs-6">
                Masukkan email dan password untuk login
            </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="submit" class="form w-100">

            <!-- Email -->
            <div class="fv-row mb-8">
                <InputLabel for="email" value="Email" class="form-label" />

                <TextInput
                    id="email"
                    type="email"
                    class="form-control"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div class="fv-row mb-8">
                <InputLabel for="password" value="Password" class="form-label" />

                <TextInput
                    id="password"
                    type="password"
                    class="form-control"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Remember + Forgot -->
            <div class="d-flex flex-stack mb-8">
                <label class="form-check form-check-custom form-check-solid">
                    <Checkbox
                        name="remember"
                        v-model:checked="form.remember"
                        class="form-check-input"
                    />
                    <span class="form-check-label fw-semibold text-gray-700 ms-2">
                        Remember me
                    </span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="link-primary"
                >
                    Forgot Password ?
                </Link>
            </div>

            <!-- Submit -->
            <div class="d-grid mb-10">
                <PrimaryButton
                    class="btn btn-primary"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="!form.processing">Log In</span>
                    <span v-else>Processing...</span>
                </PrimaryButton>
            </div>

            <!-- Register Link (Tambahan) -->
            <div class="text-center">
                <span class="text-gray-600 fw-semibold fs-6">
                    Belum punya akun?
                </span>
                <Link
                    :href="route('register')"
                    class="link-primary fw-semibold ms-1"
                >
                    Register
                </Link>
            </div>

        </form>
    </GuestLayout>
</template>