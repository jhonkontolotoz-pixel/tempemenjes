<script setup>
import Sidebar from '@/Layouts/Sidebar.vue'
import { onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()

// =========================
// METRONIC INIT
// =========================
onMounted(() => {
    if (window.KTMenu) {
        window.KTMenu.createInstances()
    }
})

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
<div id="kt_app_root" class="app-root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

        <!-- SIDEBAR -->
        <Sidebar />

        <!-- WRAPPER -->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

            <!-- HEADER -->
            <div id="kt_app_header" class="app-header align-items-stretch">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between">
                    <div class="d-flex align-items-center">
                        <h1 class="text-dark fw-bold fs-3">Dashboard</h1>
                    </div>
                </div>
            </div>

            <!-- MAIN -->
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <div class="d-flex flex-column flex-column-fluid">

                    <!-- CONTENT -->
                    <div id="kt_app_content" class="app-content flex-column-fluid">
                        <div class="app-container container-fluid">
                            <slot />
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</template>