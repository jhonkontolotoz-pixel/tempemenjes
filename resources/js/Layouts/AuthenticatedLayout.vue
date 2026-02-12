<script setup>
import Sidebar from '@/Layouts/Sidebar.vue'
import { onMounted } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import { useTheme } from '@/Composables/useTheme'
import Swal from 'sweetalert2'

const page = usePage()
const { isDark, toggleTheme } = useTheme()

// =========================
// RESET BODY STATE (ANTI SCROLL LOCK)
// =========================
const resetBodyState = () => {
    // Remove modal & drawer classes
    document.body.classList.remove('modal-open')
    document.body.classList.remove('drawer-open')
    document.body.classList.remove('overflow-hidden')
    
    // Remove SweetAlert2 classes
    document.body.classList.remove('swal2-shown', 'swal2-height-auto')
    
    // Reset overflow di body & html
    document.body.style.overflow = ''
    document.body.style.position = ''
    document.body.style.width = ''
    document.body.style.paddingRight = ''
    document.documentElement.style.overflow = ''
    
    // Remove modal backdrop jika ada
    const modalBackdrop = document.querySelector('.modal-backdrop')
    if (modalBackdrop) {
        modalBackdrop.remove()
    }
    
    // Remove swal2 container jika ada yang tertinggal
    const swalContainer = document.querySelector('.swal2-container')
    if (swalContainer && !swalContainer.classList.contains('swal2-shown')) {
        swalContainer.remove()
    }
}

// Jalankan saat pertama mount
onMounted(() => {
    resetBodyState()
})

// =========================
// LOGOUT HANDLER - DIPERBAIKI
// =========================
const handleLogout = () => {
    Swal.fire({
        title: 'Yakin mau logout?',
        text: 'Kamu akan keluar dari sistem',
        icon: 'warning',

        showCancelButton: true,
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal',

        buttonsStyling: true,

        customClass: {
            actions: 'd-flex justify-content-center gap-3'
        },

        background: 'var(--bs-body-bg)',
        color: 'var(--bs-body-color)',

        didOpen: (popup) => {
            const title = popup.querySelector('.swal2-title')
            if (title) {
                title.style.color = 'var(--bs-body-color)'
            }
        },

        didClose: () => {
            resetBodyState()
        }

    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('logout'), {}, {
                preserveScroll: false,
                preserveState: false,

                onBefore: () => {
                    document.body.style.pointerEvents = 'none'
                },

                onSuccess: () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Logout Berhasil!',
                        text: 'Sampai jumpa lagi 👋',
                        timer: 1500,
                        showConfirmButton: false,

                        background: 'var(--bs-body-bg)',
                        color: 'var(--bs-body-color)',

                        didOpen: (popup) => {
                            const title = popup.querySelector('.swal2-title')
                            if (title) {
                                title.style.color = 'var(--bs-body-color)'
                            }
                        },

                        didClose: () => {
                            resetBodyState()
                            document.body.style.pointerEvents = ''
                        }
                    })
                },

                onError: () => {
                    document.body.style.pointerEvents = ''

                    Swal.fire({
                        icon: 'error',
                        title: 'Logout Gagal!',
                        text: 'Terjadi kesalahan, coba lagi',
                        timer: 2000,
                        showConfirmButton: false,

                        background: 'var(--bs-body-bg)',
                        color: 'var(--bs-body-color)',

                        didOpen: (popup) => {
                            const title = popup.querySelector('.swal2-title')
                            if (title) {
                                title.style.color = 'var(--bs-body-color)'
                            }
                        }
                    })
                },

                onFinish: () => {
                    resetBodyState()
                    document.body.style.pointerEvents = ''
                }
            })
        }
    })
}

// =========================
// TOAST CONFIG
// =========================
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didClose: () => {
        resetBodyState()
    }
})

// =========================
// FLASH WATCHER - DIPERBAIKI
// =========================
router.on('success', (event) => {
    const flash = event.detail.page.props.flash
    if (!flash) return

    // ✅ Delay sedikit biar tidak konflik dengan navigasi
    setTimeout(() => {
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
    }, 200)
})
</script>

<template>
<div id="kt_app_root" class="app-root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

        <!-- SIDEBAR -->
        <Sidebar />

        <!-- WRAPPER -->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

            <!-- HEADER -->
            <div id="kt_app_header" class="app-header app-header-compact">
                <div class="app-container container-fluid d-flex align-items-center justify-content-between py-2">
                    
                    <!-- Left: Page Title -->
                    <div class="d-flex align-items-center">
                        <h1 class="text-gray-900 fw-bold fs-4 mb-0">Dashboard</h1>
                    </div>

                    <!-- Right: Navbar Items -->
                    <div class="app-navbar flex-shrink-0 d-flex align-items-center gap-2">
                        
                        <!-- Dark Mode Toggle -->
                        <div class="app-navbar-item">
                            <button 
                                @click="toggleTheme"
                                class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px"
                                data-bs-toggle="tooltip"
                                data-bs-placement="bottom"
                                :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
                            >
                                <!-- Sun Icon (when Dark Mode is ON) -->
                                <i v-if="isDark" class="ki-outline ki-night-day fs-3"></i>
                                <!-- Moon Icon (when Light Mode is ON) -->
                                <i v-else class="ki-outline ki-moon fs-3"></i>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="app-navbar-item" id="kt_header_user_menu_toggle">
                            <div 
                                class="cursor-pointer symbol symbol-30px"
                                data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                                data-kt-menu-attach="parent"
                                data-kt-menu-placement="bottom-end"
                            >
                                <img src="/media/avatars/300-14.jpg" alt="user" />
                            </div>
                            
                            <!-- User Menu Dropdown -->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                                
                                <!-- User Info -->
                                <div class="menu-item px-3">
                                    <div class="menu-content d-flex align-items-center px-3">
                                        <div class="symbol symbol-50px me-5">
                                            <img alt="Logo" src="/media/avatars/300-14.jpg" />
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold d-flex align-items-center fs-5">
                                                {{ page.props.auth.user.name }}
                                            </div>
                                            <span class="fw-semibold text-muted fs-7">
                                                {{ page.props.auth.user.email }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="separator my-2"></div>

                                <!-- Profile -->
                                <div class="menu-item px-5">
                                    <Link href="/profile" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-outline ki-profile-circle fs-4"></i>
                                        </span>
                                        My Profile
                                    </Link>
                                </div>

                                <!-- Settings -->
                                <div class="menu-item px-5">
                                    <Link href="/settings" class="menu-link px-5">
                                        <span class="menu-icon">
                                            <i class="ki-outline ki-setting-2 fs-4"></i>
                                        </span>
                                        Settings
                                    </Link>
                                </div>

                                <div class="separator my-2"></div>

                                <!-- Theme Toggle Switch -->
                                <div class="menu-item px-5">
                                    <div class="menu-link px-5 d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="ki-outline ki-moon fs-4 me-2"></i>
                                            Dark Mode
                                        </span>
                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                :checked="isDark"
                                                @change="toggleTheme"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="separator my-2"></div>

                                <!-- Logout -->
                                <div class="menu-item px-5">
                                    <a 
                                        href="#"
                                        @click.prevent="handleLogout"
                                        class="menu-link px-5"
                                    >
                                        <span class="menu-icon">
                                            <i class="ki-outline ki-exit-right fs-4"></i>
                                        </span>
                                        Sign Out
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- End Navbar -->

                </div>
            </div>
            <!-- End Header -->

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

<style scoped>
/* Header Compact & Solid */
.app-header-compact {
    min-height: 55px !important;
    height: 55px !important;
    background-color: var(--bs-app-header-bg-color) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid var(--bs-border-color);
}

/* Light mode header */
:root {
    --bs-app-header-bg-color: #ffffff;
}

/* Dark mode header */
[data-bs-theme="dark"] {
    --bs-app-header-bg-color: #1e1e2d;
}

/* Smooth transition for theme toggle button */
.btn-icon {
    transition: all 0.3s ease;
}

.btn-icon:hover {
    transform: scale(1.1);
}

/* Smooth icon transition */
i {
    transition: all 0.2s ease;
}

/* User menu animation */
.menu {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Spacing untuk navbar items */
.app-navbar {
    gap: 0.5rem;
}
</style>