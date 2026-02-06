<script setup>
import { Link } from '@inertiajs/vue3'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import { watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()

// =========================
// TOAST CONFIG
// =========================
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
})

// =========================
// FLASH WATCHER (GLOBAL)
// =========================
watch(
    () => page.props.flash,
    (flash) => {
        if (!flash) return

        if (flash.success) {
            Toast.fire({
                icon: 'success',
                title: flash.success,
            })
        }

        if (flash.error) {
            Toast.fire({
                icon: 'error',
                title: flash.error,
            })
        }
    },
    { immediate: true }
)
</script>

<template>
  <div class="d-flex flex-column flex-root lock-wrapper" id="kt_app_root">
    
    <div
      class="bgi-size-cover bgi-position-center bgi-no-repeat full-viewport-bg"
      style="background-image: url('/media/auth/bg12-dark.jpg');"
    >
      <div class="d-flex flex-column flex-column-fluid flex-lg-row h-100">

        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
          <div class="d-flex flex-center flex-lg-start flex-column">
            
            <Link href="/" class="mb-7">
              <ApplicationLogo class="h-60px" />
            </Link>

            <h2 class="text-white fw-normal m-0">
              Alat pencitraan merek yang dirancang untuk bisnis Anda
            </h2>

          </div>
        </div>

        <div
          class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20"
        >
          <div
            class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-10 p-lg-20"
          >
            <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10">
              
              <slot />

            </div>
          </div>
          </div>
        </div>
    </div>
  </div>
</template>

<style scoped>
/* KUNCI LAYAR UTAMA */
.lock-wrapper {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    overflow: hidden !important; /* Matikan scroll browser */
    z-index: 999;
}

.full-viewport-bg {
    height: 100vh !important;
    width: 100vw !important;
}

/* Memastikan konten di dalam card bisa discroll sedikit jika layar HP terlalu pendek 
   tapi background tetap terkunci diam */
.bg-body {
    max-height: 90vh;
    overflow-y: auto;
}

/* Sembunyikan scrollbar agar tetap terlihat clean */
.bg-body::-webkit-scrollbar {
    width: 0px;
}

/* CSS Global untuk memastikan body tidak bergerak */
:global(body) {
    overflow: hidden !important;
    position: fixed !important;
    width: 100%;
}
</style>