<script setup>
import { computed, onMounted, watch } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()
const user = computed(() => page.props.auth?.user)

// =========================
// ACTIVE ROUTE DETECTION
// =========================
const currentUrl = computed(() => page.url)

const isActive = (path) => {
    return currentUrl.value.startsWith(path)
}

const isDashboardGroupActive = computed(() => {
    const url = currentUrl.value
    return (
        url === '/' ||
        url.startsWith('/project') ||
        url.startsWith('/about')
    )
})

const isPagesGroupActive = computed(() => {
    const url = currentUrl.value
    return (
        url.startsWith('/account') ||
        url.startsWith('/settings') ||
        url.startsWith('/profile') ||
        url.startsWith('/users')
    )
})

const isAppsGroupActive = computed(() => {
    const url = currentUrl.value
    return (
        url.startsWith('/apps/ecommerce') ||
        url.startsWith('/apps/pos') ||
        url.startsWith('/apps/management-user') ||
        url.startsWith('/apps/customer') ||
        url.startsWith('/apps/product')
    )
})

// =========================
// LOGOUT
// =========================
const handleLogout = () => {
    Swal.fire({
        title: 'Yakin logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(route('logout'))
        }
    })
}

// =========================
// METRONIC INIT
// =========================
const reinitMenu = () => {
    if (window.KTMenu) {
        window.KTMenu.createInstances()
    }
}

onMounted(() => {
    reinitMenu()
})

watch(() => page.url, () => {
    setTimeout(() => {
        reinitMenu()
    }, 50)
})
</script>

<template>
<div id="kt_app_sidebar"
     class="app-sidebar flex-column"
     data-kt-drawer="true"
     data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}"
     data-kt-drawer-overlay="true"
     data-kt-drawer-width="250px"
     data-kt-drawer-direction="start"
     data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!-- LOGO -->
    <div class="app-sidebar-logo px-6 py-5 text-center">
        <Link href="/">
            <img src="/media/logos/custom-1.png"
                 alt="Logo"
                 class="h-45px" />
        </Link>
    </div>

    <!-- MENU -->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div class="app-sidebar-wrapper px-4">
            <div class="menu menu-column menu-rounded menu-sub-indention"
                 data-kt-menu="true">

                <!-- DASHBOARD -->
                <div class="menu-item menu-accordion"
                     data-kt-menu-trigger="click"
                     :class="{ show: isDashboardGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-home-2 fs-2 text-gray-300"></i>
                        </span>
                        <span class="menu-title text-gray-100">Dashboard</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isDashboardGroupActive }">

                        <div class="menu-item">
                            <Link href="/" class="menu-link"
                                  :class="{ active: currentUrl === '/' }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Default</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/project" class="menu-link"
                                  :class="{ active: isActive('/project') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Project</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/about" class="menu-link"
                                  :class="{ active: isActive('/about') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">About</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- APPS -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isAppsGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-abstract-45 fs-2 text-gray-300"></i>
                        </span>
                        <span class="menu-title text-gray-100">Apps</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isAppsGroupActive }">

                        <div class="menu-item">
                            <Link href="/apps/ecommerce" class="menu-link"
                                  :class="{ active: isActive('/apps/ecommerce') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">E-Commerce</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/apps/pos" class="menu-link"
                                  :class="{ active: isActive('/apps/pos') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">POS System</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/apps/management-user" class="menu-link"
                                  :class="{ active: isActive('/apps/management-user') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Management User</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/apps/customer" class="menu-link"
                                  :class="{ active: isActive('/apps/customer') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Customer</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/apps/product" class="menu-link"
                                  :class="{ active: isActive('/apps/product') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Product</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- PAGES -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isPagesGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-abstract-26 fs-2 text-gray-300"></i>
                        </span>
                        <span class="menu-title text-gray-100">Pages</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isPagesGroupActive }">

                        <div class="menu-item">
                            <Link href="/account" class="menu-link"
                                  :class="{ active: isActive('/account') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Account</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/settings" class="menu-link"
                                  :class="{ active: isActive('/settings') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Settings</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/profile" class="menu-link"
                                  :class="{ active: isActive('/profile') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Profile</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/users" class="menu-link"
                                  :class="{ active: isActive('/users') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">List User</span>
                            </Link>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- USER FOOTER -->
    <div class="app-sidebar-footer px-6 py-4 border-top">
        <div class="d-flex flex-column text-gray-200">
            <span class="fw-semibold">{{ user?.name }}</span>
            <span class="fs-8 text-gray-400">{{ user?.email }}</span>

            <button @click="handleLogout"
                    class="btn btn-sm btn-light-danger mt-3">
                Logout
            </button>
        </div>
    </div>

</div>
</template>