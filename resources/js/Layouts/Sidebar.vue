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
    return currentUrl.value === path || currentUrl.value.startsWith(path + '/')
}

// =========================
// NOTIFICATIONS
// =========================
const notifications = {
    tasks: 6,
    activities: 24,
    projects: 3
}

// =========================
// GROUP ACTIVE STATE
// =========================
const isDashboardGroupActive = computed(() => {
    const url = currentUrl.value
    return url.startsWith('/dashboard') || url === '/social'
})

const isAppsGroupActive = computed(() => {
    const url = currentUrl.value
    return (
        url.startsWith('/apps/pos') ||
        url.startsWith('/apps/management-user') ||
        url.startsWith('/apps/customer') ||
        url.startsWith('/apps/product') ||
        url.startsWith('/apps/financeperform')
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

const isHelpGroupActive = computed(() => currentUrl.value.startsWith('/help'))

const isProjectGroupActive = computed(() => currentUrl.value.startsWith('/project'))

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
<div id="kt_app_sidebar" class="app-sidebar flex-column bg-dark"
     data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
     data-kt-drawer-activate="{default: true, lg: false}"
     data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
     data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!-- LOGO -->
    <div class="app-sidebar-logo px-6 py-9">
        <Link href="/">
            <img src="/media/logos/custom-1.png" alt="Logo" class="h-50px app-sidebar-logo-default" />
        </Link>
    </div>

    <!-- MENU -->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5 mx-3"
             data-kt-scroll="true" data-kt-scroll-height="auto"
             data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
             data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px">

            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true">

                <!-- TASKS -->
                <div class="menu-item">
                    <Link href="/tasks" class="menu-link" :class="{ active: isActive('/tasks') }">
                        <span class="menu-icon"><i class="ki-outline ki-element-11 fs-2"></i></span>
                        <span class="menu-title text-gray-100">Tasks</span>
                        <span class="badge badge-primary badge-sm">{{ notifications.tasks }}</span>
                    </Link>
                </div>

                <!-- ACTIVITIES -->
                <div class="menu-item">
                    <Link href="/activities" class="menu-link" :class="{ active: isActive('/activities') }">
                        <span class="menu-icon"><i class="ki-outline ki-calendar fs-2"></i></span>
                        <span class="menu-title text-gray-100">Activities</span>
                        <span class="badge badge-danger badge-sm">{{ notifications.activities }}</span>
                    </Link>
                </div>

                <!-- DASHBOARD -->
                <div class="menu-item menu-accordion" data-kt-menu-trigger="click" :class="{ show: isDashboardGroupActive }">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-home-2 fs-2"></i></span>
                        <span class="menu-title text-gray-100">Dashboard</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion" :class="{ show: isDashboardGroupActive }">
                        <div class="menu-item">
                            <Link href="/" class="menu-link" :class="{ active: currentUrl.value === '/' }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Default</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/dashboard/social" class="menu-link" :class="{ active: isActive('/dashboard/marketing') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Social</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/dashboard/school" class="menu-link" :class="{ active: isActive('/dashboard/school') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">School</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- APPS -->
                <div class="menu-item menu-accordion mt-5" data-kt-menu-trigger="click" :class="{ show: isAppsGroupActive }">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-abstract-45 fs-2"></i></span>
                        <span class="menu-title text-gray-100">Apps</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion" :class="{ show: isAppsGroupActive }">
                        <div class="menu-item">
                            <Link href="/apps/pos" class="menu-link" :class="{ active: isActive('/apps/pos') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">POS System</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/apps/management-user" class="menu-link" :class="{ active: isActive('/apps/management-user') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Management User</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/apps/customer" class="menu-link" :class="{ active: isActive('/apps/customer') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Customer</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/apps/product" class="menu-link" :class="{ active: isActive('/apps/product') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Product</span>
                            </Link>
                        </div>
                          <div class="menu-item">
                            <Link href="/apps/financeperform" class="menu-link" :class="{ active: isActive('/apps/product') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Finance Perfomances</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/apps/invoice" class="menu-link" :class="{ active: isActive('/apps/pos') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Invoice Manager</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- PAGES -->
                <div class="menu-item menu-accordion mt-5" data-kt-menu-trigger="click" :class="{ show: isPagesGroupActive }">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-2"></i></span>
                        <span class="menu-title text-gray-100">Pages</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion" :class="{ show: isPagesGroupActive }">
                        <div class="menu-item">
                            <Link href="/account" class="menu-link" :class="{ active: isActive('/account') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Account</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/profile" class="menu-link" :class="{ active: isActive('/profile') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Profile</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/users" class="menu-link" :class="{ active: isActive('/users') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Contact</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/apps/management-user/roles" class="menu-link" :class="{ active: isActive('/apps/management-user/roles') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Roles Permissions</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/apps/management-user/permissions" class="menu-link" :class="{ active: isActive('/apps/management-user/permissions') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Calendar</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- HELP -->
                <div class="menu-item menu-accordion mt-5" data-kt-menu-trigger="click" :class="{ show: isHelpGroupActive }">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-information fs-2"></i></span>
                        <span class="menu-title text-gray-100">Help</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion" :class="{ show: isHelpGroupActive }">
                        <div class="menu-item">
                            <Link href="/help/component" class="menu-link" :class="{ active: isActive('/help/component') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Component</span>
                            </Link>
                        </div>
                        <div class="menu-item">
                            <Link href="/help/document" class="menu-link" :class="{ active: isActive('/help/document') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Document</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- PROJECT -->
                <div class="menu-item menu-accordion mt-5" data-kt-menu-trigger="click" :class="{ show: isProjectGroupActive }">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-folder fs-2"></i></span>
                        <span class="menu-title text-gray-100">Project</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion" :class="{ show: isProjectGroupActive }">
    
    <div class="menu-item">
        <a href="https://github.com/jhonkontolotoz-pixel/kasir-ulfah"
           class="menu-link"
           target="_blank"
           rel="noopener">
            <span class="menu-bullet">
                <span class="bullet bullet-dot"></span>
            </span>
            <span class="menu-title">1. POS Sistem</span>
        </a>
    </div>

    <div class="menu-item">
        <a href="https://github.com/jhonkontolotoz-pixel/dashboard-management"
           class="menu-link"
           target="_blank"
           rel="noopener">
            <span class="menu-bullet">
                <span class="bullet bullet-dot"></span>
            </span>
            <span class="menu-title">2. Login Register SPA API Only</span>
        </a>
    </div>

</div>

                </div>

            </div>
        </div>
    </div>

    <!-- USER FOOTER -->
    <div class="app-sidebar-footer d-flex align-items-center px-8 pb-10">
        <div class="d-flex flex-stack flex-row-fluid">
            <div class="d-flex align-items-center cursor-pointer" 
                 data-kt-menu-trigger="{default: 'click'}" 
                 data-kt-menu-attach="body" 
                 data-kt-menu-placement="top-start">
                <div class="symbol symbol-circle symbol-35px me-3">
                    <img src="/media/avatars/300-14.jpg" alt="user" />
                </div>
                <div class="d-flex flex-column">
                    <span class="text-white fw-bold fs-7 lh-1">{{ user?.name || 'Alice' }}</span>
                    <span class="text-gray-500 fw-semibold fs-8 lh-1 mt-1">Welcome</span>
                </div>
            </div>

            <button @click="handleLogout" class="btn btn-icon btn-sm btn-custom btn-danger w-30px h-30px">
                <i class="ki-outline ki-exit-right fs-2"></i>
            </button>
        </div>
    </div>
</div>
</template>