import '../css/app.css'
import './bootstrap'

import { createInertiaApp, usePage } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h, watch } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import Swal from 'sweetalert2'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

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

        // GLOBAL FLASH WATCHER
        vueApp.mixin({
            setup() {
                const page = usePage()

                watch(
                    () => page.props.flash,
                    (flash) => {
                        if (!flash) return

                        if (flash.success) {
                            Swal.fire({
                                icon: 'success',
                                title: flash.success,
                                timer: 2000,
                                showConfirmButton: false,
                            })
                        }

                        if (flash.error) {
                            Swal.fire({
                                icon: 'error',
                                title: flash.error,
                                timer: 2000,
                                showConfirmButton: false,
                            })
                        }
                    },
                    { deep: true }
                )
            },
        })

        vueApp.mount(el)
    },

    progress: {
        color: '#4B5563',
    },
})