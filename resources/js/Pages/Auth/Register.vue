<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

// Toast instance (mirip login/logout)
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer
    toast.onmouseleave = Swal.resumeTimer
  },
})

const submit = () => {
  // Validasi custom
  if (!form.name) {
    Toast.fire({ icon: 'error', title: 'Nama wajib diisi!' })
    return
  }

  if (!form.email) {
    Toast.fire({ icon: 'error', title: 'Email wajib diisi!' })
    return
  }

  if (!form.email.includes('@')) {
    Toast.fire({ icon: 'error', title: 'Email harus valid, contoh: example@mail.com' })
    return
  }

  if (!form.password) {
    Toast.fire({ icon: 'error', title: 'Password wajib diisi!' })
    return
  }

  if (form.password.length < 8) {
    Toast.fire({ icon: 'error', title: 'Password minimal 8 karakter!' })
    return
  }

  if (form.password !== form.password_confirmation) {
    Toast.fire({ icon: 'error', title: 'Password dan konfirmasi tidak sama!' })
    return
  }

  // Submit form
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}

</script>

<template>
  <GuestLayout>
    <Head title="Register" />

    <!-- Heading -->
    <div class="text-center mb-11">
      <h1 class="text-gray-900 fw-bolder mb-3">Create Account</h1>
      <div class="text-gray-500 fw-semibold fs-6">Isi data untuk membuat akun baru</div>
    </div>

    <!-- Form -->
    <form @submit.prevent="submit" class="form w-100" novalidate>

      <!-- Name -->
      <div class="fv-row mb-8">
        <InputLabel for="name" value="Nama Lengkap" class="form-label" />
        <TextInput
          id="name"
          type="text"
          class="form-control bg-transparent"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
        />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <!-- Email -->
      <div class="fv-row mb-8">
        <InputLabel for="email" value="Email" class="form-label" />
        <TextInput
          id="email"
          type="email"
          class="form-control bg-transparent"
          v-model="form.email"
          required
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
          class="form-control bg-transparent"
          v-model="form.password"
          required
          autocomplete="new-password"
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <!-- Confirm Password -->
      <div class="fv-row mb-8">
        <InputLabel for="password_confirmation" value="Confirm Password" class="form-label" />
        <TextInput
          id="password_confirmation"
          type="password"
          class="form-control bg-transparent"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
        />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <!-- Actions -->
      <div class="d-flex flex-stack mb-8">
        <Link :href="route('login')" class="link-primary fw-semibold">Sudah Punya Akun?</Link>
      </div>

      <!-- Submit -->
      <div class="d-grid mb-10">
        <PrimaryButton
          class="btn btn-primary"
          :class="{ 'opacity-50': form.processing }"
          :disabled="form.processing"
        >
          <span v-if="!form.processing">Register</span>
          <span v-else>
            Harap Sabarrrr...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
          </span>
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>