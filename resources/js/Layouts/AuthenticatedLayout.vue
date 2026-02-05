<script setup>
import Sidebar from '@/Layouts/Sidebar.vue'
import { onMounted } from 'vue'

onMounted(() => {
    if (window.KTMenu) {
        KTMenu.createInstances()
    }
})
</script>

<template>
<div id="kt_app_root" class="app-root">
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

        <!-- SIDEBAR -->
        <Sidebar />

        <!-- WRAPPER -->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

            <!-- HEADER (minimal dulu, biar layout ga rusak) -->
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