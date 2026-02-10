<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

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
  // Validasi frontend
  if (!form.email) {
    Swal.fire({ 
      icon: 'error', 
      title: 'Email wajib diisi!',
      timer: 2000,
      showConfirmButton: false
    })
    return
  }

  if (!form.email.includes('@')) {
    Swal.fire({ 
      icon: 'error', 
      title: 'Email harus valid, contoh: example@mail.com',
      timer: 2000,
      showConfirmButton: false
    })
    return
  }

  if (!form.password) {
    Swal.fire({ 
      icon: 'error', 
      title: 'Password wajib diisi!',
      timer: 2000,
      showConfirmButton: false
    })
    return
  }

  // Kirim form ke backend
  form.post(route('login'), {
    onFinish: () => {
      form.reset('password')
    },
    onSuccess: () => {
      // ✅ TAMPILKAN SWEETALERT LOGIN BERHASIL
      Swal.fire({
        icon: 'success',
        title: 'Login Berhasil!',
        text: 'Selamat datang kembali 🎉',
        timer: 1500,
        showConfirmButton: false,
        didClose: () => {
          // Reset body state setelah swal tutup
          document.body.style.overflow = ''
          document.body.style.position = ''
          document.body.style.width = ''
          document.body.classList.remove('swal2-shown', 'swal2-height-auto')
        }
      })
    },
    onError: () => {
      Swal.fire({
        icon: 'error',
        title: 'Login gagal!',
        text: 'Salah input kali, Bro/Sist. Kagak ada di daftar nih',
        timer: 2000,
        showConfirmButton: false
      })
    }
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
        <form @submit.prevent="submit" class="form w-100" novalidate>

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
                    <span v-else>Sabarrr...</span>
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