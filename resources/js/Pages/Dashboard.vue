<script setup>
import Authenticated from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const page = usePage()

// Profile data
const profile = ref({
    name: 'Rudi max',
    role: 'Developer',
    avatar: '/media/avatars/rud.jpg',
    earnings: '$4,500',
    projects: 99,
    tasks: 0
})

// Recent activities
const activities = ref([
    {
        id: 1,
        title: 'Create FureStibe branding logo',
        type: 'task',
        status: 'pending',
        time: '2 hours ago',
        icon: 'ki-abstract-26',
        color: 'primary'
    },
    {
        id: 2,
        title: 'Meeting with client',
        type: 'event',
        status: 'completed',
        time: '5 hours ago',
        icon: 'ki-calendar',
        color: 'success'
    },
    {
        id: 3,
        title: 'Submit project documentation',
        type: 'task',
        status: 'pending',
        time: '1 day ago',
        icon: 'ki-file',
        color: 'warning'
    },
    {
        id: 4,
        title: 'Code review session',
        type: 'event',
        status: 'completed',
        time: '2 days ago',
        icon: 'ki-code',
        color: 'info'
    }
])
</script>

<template>
    <Head :title="`Dashboard - ${page.props.auth.user.name}`" />

    <Authenticated>
        <div class="container-fluid">
            <!--begin::Profile Header-->
            <div class="card mb-5 mb-xl-10">
                <div class="card-body pt-9 pb-0">
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <!--begin::Avatar-->
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-fixed position-relative">
                                <img :src="profile.avatar" alt="avatar" />
                                <div class="position-absolute translate-middle bottom-0 start-100 mb-n5 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                            </div>
                        </div>
                        <!--end::Avatar-->

                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-gray-900 fs-2 fw-bold me-1">{{ profile.name }}</span>
                                        <i class="ki-outline ki-verify fs-1 text-primary"></i>
                                    </div>
                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        <span class="d-flex align-items-center text-gray-500 me-5 mb-2">
                                            <i class="ki-outline ki-profile-circle fs-4 me-1"></i>{{ profile.role }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!--begin::Stats-->
                            <div class="d-flex flex-wrap">
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-arrow-up fs-3 text-success me-2"></i>
                                        <div class="fs-2 fw-bold">{{ profile.earnings }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-500">Earnings</div>
                                </div>

                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-arrow-down fs-3 text-danger me-2"></i>
                                        <div class="fs-2 fw-bold">{{ profile.projects }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-500">Projects</div>
                                </div>

                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="ki-outline ki-arrow-up fs-3 text-success me-2"></i>
                                        <div class="fs-2 fw-bold">{{ profile.tasks }}</div>
                                    </div>
                                    <div class="fw-semibold fs-6 text-gray-500">Tasks</div>
                                </div>
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Info-->
                    </div>
                </div>
            </div>
            <!--end::Profile Header-->

            <!--begin::Activities-->
            <div class="row g-6 g-xl-9">
                <div class="col-12">
                    <div class="card">
                        <!--begin::Header-->
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">Recent Activities</span>
                                <span class="text-muted mt-1 fw-semibold fs-7">Your latest activities and tasks</span>
                            </h3>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-sm btn-light-primary">
                                    <i class="ki-outline ki-plus fs-2"></i>New Activity
                                </a>
                            </div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body py-3">
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-200px">Activity</th>
                                            <th class="min-w-100px">Type</th>
                                            <th class="min-w-100px">Status</th>
                                            <th class="min-w-100px">Time</th>
                                            <th class="min-w-100px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="activity in activities" :key="activity.id">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-45px me-5">
                                                        <span :class="`symbol-label bg-light-${activity.color}`">
                                                            <i :class="`ki-outline ${activity.icon} fs-2x text-${activity.color}`"></i>
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-start flex-column">
                                                        <a href="#" class="text-dark fw-bold text-hover-primary fs-6">
                                                            {{ activity.title }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                    {{ activity.type }}
                                                </span>
                                            </td>
                                            <td>
                                                <span 
                                                    class="badge"
                                                    :class="activity.status === 'completed' ? 'badge-light-success' : 'badge-light-warning'"
                                                >
                                                    {{ activity.status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-muted fw-semibold text-muted d-block fs-7">
                                                    {{ activity.time }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                    <i class="ki-outline ki-pencil fs-2"></i>
                                                </a>
                                                <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                    <i class="ki-outline ki-trash fs-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                </div>
            </div>
            <!--end::Activities-->
        </div>
    </Authenticated>
</template>

<style scoped>
.min-w-125px {
    min-width: 125px;
}

.min-w-100px {
    min-width: 100px;
}

.min-w-200px {
    min-width: 200px;
}
</style>