<script setup>
import Sidebar from '@/Layouts/Sidebar.vue'
import { defineProps, onMounted } from 'vue'

const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    },
    breadcrumbs: {
        type: Array,
        default: () => []
    }
})

onMounted(() => {
    if (window.KTMenu) {
        window.KTMenu.createInstances()
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

    <!-- HEADER -->
<div
    id="kt_app_header"
    class="app-header py-4"
    data-kt-sticky="true"
    data-kt-sticky-name="app-header"
    data-kt-sticky-offset="{default: '200px', lg: '300px'}"
>
    <div class="app-container container-fluid d-flex justify-content-between align-items-center">

        <!-- LEFT SIDE -->
        <div class="d-flex flex-column">

            <!-- Breadcrumb -->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 mb-2">
                <li class="breadcrumb-item text-gray-600">
                    <i class="ki-outline ki-home text-gray-700 fs-6"></i>
                </li>

                <li
                    v-for="(item, index) in props.breadcrumbs"
                    :key="index"
                    class="breadcrumb-item text-gray-600"
                >
                    <i class="ki-outline ki-right fs-7 text-gray-400 mx-2"></i>
                    {{ item }}
                </li>
            </ul>

            <!-- Page Title -->
            <h1 class="page-heading text-gray-900 fw-bold fs-2 my-0">
                {{ props.title }}
            </h1>
        </div>

        <!-- RIGHT SIDE (BUTTON / ACTION SLOT) -->
        <div>
            <slot name="actions" />
        </div>

    </div>
</div>
            <!-- MAIN -->
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <div class="app-container container-fluid">
                        <slot />
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</template>