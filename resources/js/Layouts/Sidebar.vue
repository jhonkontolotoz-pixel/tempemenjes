<script setup>
import { computed, onMounted, watch } from 'vue'
import { usePage, router, Link } from '@inertiajs/vue3'
import Swal from 'sweetalert2'

const page = usePage()
const user = computed(() => page.props.auth?.user)

const currentUrl = computed(() => page.url)

// =========================
// ROUTE HELPERS
// =========================
const isActive = (path) =>
    currentUrl.value === path || currentUrl.value.startsWith(path + '/')

const isRouteGroup = (paths) =>
    paths.some(path => currentUrl.value.startsWith(path))

// =========================
// NOTIFICATIONS
// =========================
const notifications = {
    tasks: 5,
    activities: 99,
}

// =========================
// GROUP STATES
// =========================
const isDashboardGroupActive = computed(() =>
    isRouteGroup([
        '/dashboard/bloghome',
        '/dashboard/school'
    ])
)

const isAppsGroupActive = computed(() =>
    isRouteGroup([
        '/apps/pos',
        '/apps/user-list',
        '/apps/customer',
        '/apps/product',
        '/apps/financeperformances',
        '/apps/roles-permission',
        '/apps/invoice-view',
        '/apps/invoice-create'
    ])
)

const isReportsGroupActive = computed(() =>
    isRouteGroup([
        '/productviewed',
        '/sales',
        '/customer-orders',
        '/shipping'
    ])
)

const isPagesGroupActive = computed(() =>
    isRouteGroup([
        '/account-overview',
        '/profile',
        '/contact',
        '/calendar'
    ])
)

const isHelpGroupActive = computed(() =>
    isRouteGroup(['/help'])
)

const isProjectGroupActive = computed(() =>
    isRouteGroup(['/project'])
)

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
    <div class="app-sidebar-logo px-6 py-9">
        <Link href="/">
            <img src="/media/logos/custom-1.png"
                 class="h-50px"
                 alt="Logo" />
        </Link>
    </div>

    <!-- MENU -->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div class="app-sidebar-wrapper hover-scroll-overlay-y my-5 mx-3"
             data-kt-scroll="true">

            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6"
                 data-kt-menu="true">

                <!-- TASKS -->
                <div class="menu-item">
                    <Link href="/tasks"
                          class="menu-link"
                          :class="{ active: isActive('/tasks') }">
                        <span class="menu-icon"><i class="ki-outline ki-element-11 fs-2"></i></span>
                        <span class="menu-title">Tasks</span>
                        <span class="badge badge-primary badge-sm">
                            {{ notifications.tasks }}
                        </span>
                    </Link>
                </div>

                <!-- ACTIVITIES -->
                <div class="menu-item">
                    <Link href="/activities"
                          class="menu-link"
                          :class="{ active: isActive('/activities') }">
                        <span class="menu-icon"><i class="ki-outline ki-calendar fs-2"></i></span>
                        <span class="menu-title">Activities</span>
                        <span class="badge badge-danger badge-sm">
                            {{ notifications.activities }}
                        </span>
                    </Link>
                </div>

                <!-- DASHBOARD -->
                <div class="menu-item menu-accordion"
                     data-kt-menu-trigger="click"
                     :class="{ show: isDashboardGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-home-2 fs-2"></i></span>
                        <span class="menu-title">Dashboard</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isDashboardGroupActive }">

                        <div class="menu-item">
                            <Link href="/"
                                  class="menu-link"
                                  :class="{ active: isActive('/') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Default</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/dashboard/blog"
                                  class="menu-link"
                                  :class="{ active: isActive('/dashboard/bloghome') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Blog Home</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/dashboard/school"
                                  class="menu-link"
                                  :class="{ active: isActive('/dashboard/school') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">School</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- APPS -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isAppsGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-abstract-45 fs-2"></i></span>
                        <span class="menu-title">Apps</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isAppsGroupActive }">

                        <div class="menu-item"
                             v-for="item in [
                                {name:'POS System', path:'/apps/pos'},
                                {name:'User List', path:'/apps/user-list'},
                                {name:'Customer', path:'/apps/customer'},
                                {name:'Product', path:'/apps/product'},
                                {name:'Finance Performances', path:'/apps/financeperformances'},
                                {name:'Roles Permissions', path:'/apps/roles-permission'},
                                {name:'Invoice Manager', path:'/apps/invoice-view'},
                                {name:'Invoice Create', path:'/apps/invoice-create'}
                             ]"
                             :key="item.path">

                            <Link :href="item.path"
                                  class="menu-link"
                                  :class="{ active: isActive(item.path) }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">{{ item.name }}</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- REPORTS -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isReportsGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-chart fs-2"></i></span>
                        <span class="menu-title">Reports</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isReportsGroupActive }">

                        <div class="menu-item"
                             v-for="item in [
                                {name:'Product Viewed', path:'/productviewed'},
                                {name:'Sales', path:'/sales'},
                                {name:'Customer Orders', path:'/customer-orders'},
                                {name:'Shipping', path:'/shipping'}
                             ]"
                             :key="item.path">

                            <Link :href="item.path"
                                  class="menu-link"
                                  :class="{ active: isActive(item.path) }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">{{ item.name }}</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- PAGES -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isPagesGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-2"></i></span>
                        <span class="menu-title">Pages</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isPagesGroupActive }">

                        <div class="menu-item"
                             v-for="item in [
                                {name:'Account', path:'/account-overview'},
                                {name:'Profile', path:'/profile'},
                                {name:'Contact', path:'/contact'},
                                {name:'Calendar', path:'/calendar'}
                             ]"
                             :key="item.path">

                            <Link :href="item.path"
                                  class="menu-link"
                                  :class="{ active: isActive(item.path) }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">{{ item.name }}</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- HELP -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isHelpGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-information fs-2"></i></span>
                        <span class="menu-title">Help</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion"
                         :class="{ show: isHelpGroupActive }">

                        <div class="menu-item">
                            <Link href="/help/component"
                                  class="menu-link"
                                  :class="{ active: isActive('/help/component') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Component</span>
                            </Link>
                        </div>

                        <div class="menu-item">
                            <Link href="/help/document"
                                  class="menu-link"
                                  :class="{ active: isActive('/help/document') }">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Document</span>
                            </Link>
                        </div>

                    </div>
                </div>

                <!-- PROJECT -->
                <div class="menu-item menu-accordion mt-5"
                     data-kt-menu-trigger="click"
                     :class="{ show: isProjectGroupActive }">

                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-folder fs-2"></i></span>
                        <span class="menu-title">Project</span>
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="https://github.com/jhonkontolotoz-pixel/kasir-ulfah"
                               class="menu-link"
                               target="_blank">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">1. KASIR SPA</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a href="https://github.com/jhonkontolotoz-pixel/dashboard-management"
                               class="menu-link"
                               target="_blank">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">2. Login Register SPA</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- FOOTER USER -->
    <div class="app-sidebar-footer d-flex align-items-center px-8 pb-10">
        <div class="d-flex align-items-center flex-row-fluid">
            <div class="symbol symbol-circle symbol-35px me-3">
                <img src="/media/avatars/300-14.jpg" alt="user" />
            </div>
            <div class="d-flex flex-column">
                <span class="text-white fw-bold fs-7">
                    {{ user?.name || 'User' }}
                </span>
                <span class="text-gray-400 fs-8">Welcome</span>
            </div>
        </div>

        <button @click="handleLogout"
                class="btn btn-icon btn-sm btn-danger w-30px h-30px">
            <i class="ki-outline ki-exit-right fs-2"></i>
        </button>
    </div>

</div>
</template>

<style scoped>
/* Sidebar SELALU DARK - tidak peduli light/dark mode */
.sidebar-dark {
    background-color: #1e1e2d !important;
}

/* Text di sidebar selalu putih/terang */
.sidebar-dark .menu-link {
    color: #92929f;
}

.sidebar-dark .menu-link:hover {
    background-color: #1b1b28;
    color: #ffffff;
}

.sidebar-dark .menu-link.active {
    background-color: #1b1b28;
    color: #3699ff;
}

.sidebar-dark .menu-title {
    color: #92929f;
}

.sidebar-dark .menu-link:hover .menu-title {
    color: #ffffff;
}

.sidebar-dark .menu-link.active .menu-title {
    color: #3699ff;
}

.sidebar-dark .menu-icon i {
    color: #494b74;
}

.sidebar-dark .menu-link:hover .menu-icon i {
    color: #ffffff;
}

.sidebar-dark .menu-link.active .menu-icon i {
    color: #3699ff;
}

.sidebar-dark .menu-arrow {
    color: #494b74;
}

/* Scrollbar dark */
.sidebar-dark .hover-scroll-overlay-y::-webkit-scrollbar-thumb {
    background-color: #2b2b40;
}

.sidebar-dark .hover-scroll-overlay-y::-webkit-scrollbar-track {
    background-color: #1e1e2d;
}
</style>