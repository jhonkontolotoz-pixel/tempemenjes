<script setup>
/**
 * Sidebar.vue - RBAC-Aware Sidebar Component
 * 
 * Komponen sidebar yang menampilkan menu dinamis berdasarkan role user.
 * Menggunakan Metronic 8 styling dan Vue 3 Composition API.
 * 
 * Role Access:
 * - Admin: Full access (Dashboard, Customers, Products, Reports, POS, Profile)
 * - Manager: Dashboard, Customers, Products, Reports, Profile
 * - Kasir: Dashboard, POS, Profile
 * - User: Dashboard, Profile only
 */

import { computed, onMounted, watch } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import { useRole } from '@/Composables/useRole'
import Swal from 'sweetalert2'

const page = usePage()
const { isAdmin, isManager, isKasir, hasRole } = useRole()

const user = computed(() => page.props.auth?.user)
const currentUrl = computed(() => page.url)

// =========================
// ROUTE HELPERS
// =========================
const isActive = (path) => 
    currentUrl.value === path || currentUrl.value.startsWith(path + '/')

// =========================
// ROLE-BASED MENU VISIBILITY
// =========================
const canAccessCustomers = computed(() => isAdmin.value || isManager.value)
const canAccessProducts = computed(() => isAdmin.value || isManager.value)
const canAccessReports = computed(() => isAdmin.value || isManager.value)
const canAccessPOS = computed(() => isAdmin.value || isKasir.value)

// =========================
// MENU CONFIGURATION
// =========================
const menuItems = computed(() => [
    {
        id: 'dashboard',
        title: 'Dashboard',
        path: '/',
        icon: 'ki-home-2',
        show: true, // All roles can access
    },
    {
        id: 'customers',
        title: 'Customers',
        path: '/customers',
        icon: 'ki-user',
        show: canAccessCustomers.value,
        badge: null,
    },
    {
        id: 'products',
        title: 'Products',
        path: '/products',
        icon: 'ki-shop',
        show: canAccessProducts.value,
        badge: null,
    },
    {
        id: 'reports',
        title: 'Reports',
        path: '/reports',
        icon: 'ki-chart',
        show: canAccessReports.value,
        badge: null,
    },
    {
        id: 'pos',
        title: 'POS',
        path: '/pos',
        icon: 'ki-calculator',
        show: canAccessPOS.value,
        badge: { text: 'New', color: 'success' },
    },
    {
        id: 'profile',
        title: 'Profile',
        path: '/profile',
        icon: 'ki-profile-circle',
        show: true, // All roles can access
    },
])

// Filter hanya menu yang boleh ditampilkan
const visibleMenuItems = computed(() => 
    menuItems.value.filter(item => item.show)
)

// =========================
// LOGOUT HANDLER
// =========================
const handleLogout = () => {
    Swal.fire({
        title: 'Yakin logout?',
        text: 'Kamu akan keluar dari sistem',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
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
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('logout'))
        }
    })
}

// =========================
// METRONIC MENU INIT
// =========================
const reinitMenu = () => {
    if (window.KTMenu) {
        window.KTMenu.createInstances()
    }
}

onMounted(reinitMenu)

watch(() => page.url, () => {
    setTimeout(reinitMenu, 50)
})
</script>

<template>
<div id="kt_app_sidebar"
     class="app-sidebar flex-column sidebar-dark"
     data-kt-drawer="true"
     data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}"
     data-kt-drawer-overlay="true"
     data-kt-drawer-width="250px"
     data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!-- LOGO -->
    <div class="app-sidebar-logo px-6 py-8">
        <div class="d-flex align-items-center justify-content-between 
                    bg-light-dark 
                    rounded-4 px-5 py-4 
                    border border-gray-200 border-gray-700-dark">
            
            <div class="d-flex align-items-center">
                <img src="/media/logos/cuyr-1.png"
                     class="h-40px me-3"
                     alt="Logo" />
                
                <div class="d-flex flex-column">
                    <span class="fw-bold fs-4 text-gray-900 text-white-dark">
                        POS System
                    </span>
                    <span class="fs-7 text-gray-600 text-gray-400-dark">
                        {{ user?.role?.name || 'User' }}
                    </span>
                </div>
            </div>

            <i class="ki-outline ki-arrow-down fs-5 text-gray-600 text-gray-400-dark"></i>
        </div>
    </div>

    <!-- MENU -->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div class="app-sidebar-wrapper hover-scroll-overlay-y my-5 mx-3"
             data-kt-scroll="true"
             data-kt-scroll-activate="true"
             data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu"
             data-kt-scroll-offset="5px">

            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                 id="#kt_app_sidebar_menu"
                 data-kt-menu="true">

                <!-- MENU ITEMS (Role-based) -->
                <div v-for="item in visibleMenuItems" 
                     :key="item.id"
                     class="menu-item">
                    
                    <Link :href="item.path"
                          class="menu-link"
                          :class="{ active: isActive(item.path) }">
                        
                        <span class="menu-icon">
                            <i :class="`ki-outline ${item.icon} fs-2`"></i>
                        </span>
                        
                        <span class="menu-title">{{ item.title }}</span>
                        
                        <!-- Badge jika ada -->
                        <span v-if="item.badge" 
                              class="badge badge-sm"
                              :class="`badge-${item.badge.color}`">
                            {{ item.badge.text }}
                        </span>
                    </Link>
                </div>

                <!-- DIVIDER -->
                <div class="menu-item">
                    <div class="menu-content">
                        <div class="separator mx-1 my-4"></div>
                    </div>
                </div>

                <!-- ROLE INDICATOR -->
                <div class="menu-item">
                    <div class="menu-content px-3 py-3">
                        <div class="d-flex align-items-center bg-light-primary rounded p-3">
                            <i class="ki-outline ki-shield-tick fs-2x text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-gray-800 fs-7">
                                    {{ user?.role?.name || 'User' }}
                                </div>
                                <div class="text-muted fs-8">
                                    Current Role
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FOOTER USER -->
    <div class="app-sidebar-footer d-flex align-items-center px-8 pb-10" 
         id="kt_app_sidebar_footer">
        <div class="d-flex align-items-center flex-row-fluid">
            <div class="symbol symbol-circle symbol-40px me-3">
                <img :src="user?.avatar || '/media/avatars/300-14.jpg'" 
                     :alt="user?.name" />
            </div>
            <div class="d-flex flex-column flex-grow-1">
                <span class="text-white fw-bold fs-7">
                    {{ user?.name || 'User' }}
                </span>
                <span class="text-gray-400 fs-8">
                    {{ user?.email || 'user@example.com' }}
                </span>
            </div>
        </div>

        <button @click="handleLogout"
                class="btn btn-icon btn-sm btn-danger w-35px h-35px ms-2"
                title="Logout">
            <i class="ki-outline ki-exit-right fs-2"></i>
        </button>
    </div>

</div>
</template>

<style scoped>
/* ========================================
   SIDEBAR DARK MODE - Always Dark
   ======================================== */

.sidebar-dark {
    background-color: #1e1e2d !important;
}

/* Menu Links */
.sidebar-dark .menu-link {
    color: #92929f;
    transition: all 0.2s ease;
}

.sidebar-dark .menu-link:hover {
    background-color: #1b1b28;
    color: #ffffff;
}

.sidebar-dark .menu-link.active {
    background-color: #1b1b28;
    color: #3699ff;
}

/* Menu Title */
.sidebar-dark .menu-title {
    color: #92929f;
}

.sidebar-dark .menu-link:hover .menu-title {
    color: #ffffff;
}

.sidebar-dark .menu-link.active .menu-title {
    color: #3699ff;
}

/* Menu Icons */
.sidebar-dark .menu-icon i {
    color: #494b74;
}

.sidebar-dark .menu-link:hover .menu-icon i {
    color: #ffffff;
}

.sidebar-dark .menu-link.active .menu-icon i {
    color: #3699ff;
}

/* Scrollbar */
.sidebar-dark .hover-scroll-overlay-y::-webkit-scrollbar {
    width: 6px;
}

.sidebar-dark .hover-scroll-overlay-y::-webkit-scrollbar-thumb {
    background-color: #2b2b40;
    border-radius: 10px;
}

.sidebar-dark .hover-scroll-overlay-y::-webkit-scrollbar-track {
    background-color: #1e1e2d;
}

/* Badge Styling */
.badge-sm {
    padding: 4px 8px;
    font-size: 0.75rem;
}

/* Role Indicator */
.bg-light-primary {
    background-color: rgba(54, 153, 255, 0.1) !important;
}

/* Footer */
.app-sidebar-footer {
    border-top: 1px solid #2b2b40;
}

/* Separator */
.separator {
    height: 1px;
    background-color: #2b2b40;
}
</style>