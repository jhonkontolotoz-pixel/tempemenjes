import '../css/app.css'
import './bootstrap'

import { createInertiaApp, router } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

// ========================================
// ⭐ FIX SCROLL ISSUE - RESET BODY STATE
// ========================================
const resetBodyState = () => {
    // Remove all scroll-blocking classes
    document.body.classList.remove('modal-open')
    document.body.classList.remove('drawer-open')
    document.body.classList.remove('overflow-hidden')
    document.body.classList.remove('swal2-shown')
    document.body.classList.remove('swal2-height-auto')
    
    // Reset inline styles
    document.body.style.overflow = ''
    document.body.style.position = ''
    document.body.style.width = ''
    document.body.style.paddingRight = ''
    
    // Also reset html element
    document.documentElement.style.overflow = ''
    
    // Remove modal backdrops
    const modalBackdrop = document.querySelector('.modal-backdrop')
    if (modalBackdrop) {
        modalBackdrop.remove()
    }
    
    // Remove swal containers yang tertinggal
    const swalContainer = document.querySelector('.swal2-container')
    if (swalContainer && !swalContainer.classList.contains('swal2-shown')) {
        swalContainer.remove()
    }
}

// Reset sebelum navigasi dimulai
router.on('before', () => {
    resetBodyState()
})

// Reset setelah navigasi selesai
router.on('navigate', () => {
    resetBodyState()
})

router.on('finish', () => {
    // Delay sedikit untuk memastikan DOM sudah update
    setTimeout(() => {
        resetBodyState()
    }, 50)
})

router.on('success', () => {
    setTimeout(() => {
        resetBodyState()
    }, 100)
})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {
        const vueApp = createApp({
            render: () => h(App, props),
        })

        vueApp.use(plugin)
        vueApp.use(ZiggyVue)

        // ❌ HAPUS GLOBAL FLASH WATCHER DARI SINI
        // Biar tidak konflik dengan flash watcher di AuthenticatedLayout
        // Flash message akan dihandle di masing-masing layout

        vueApp.mount(el)
        
        // ⭐ Reset body state setelah app mounted
        resetBodyState()
    },

    progress: {
        color: '#ffaf1b',
    },
})