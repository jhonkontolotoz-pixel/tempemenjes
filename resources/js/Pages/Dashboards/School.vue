<script setup>
import Authenticated from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage, Link } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

// Schedule data (Today)
const todaySchedule = [
    {
        time: '08:30-09:12am',
        lesson: 'lesson 1: Math class',
        location: 'Cabinet: 45',
        breakTime: 'Break 15min'
    },
    {
        time: '09:30-10:50am',
        lesson: 'lesson 2: History class',
        location: 'Cabinet: 12',
        breakTime: 'Break 20min'
    },
    {
        time: '10:35-11:20am',
        lesson: 'lesson 3: Sports class',
        location: 'Court: Main',
        breakTime: 'Break 15min'
    },
    {
        time: '12:40-13:25pm',
        lesson: 'lesson 4: Chemistry class',
        location: 'Cabinet: 19',
        breakTime: null
    }
]

// Overview Stats
const overviewStats = [
    {
        icon: 'ki-outline ki-timer',
        title: 'Attendance',
        description: 'Great, you always attending class. keep it up',
        value: '73',
        total: '76',
        percentage: '95%',
        percentageClass: 'badge-light-success'
    },
    {
        icon: 'ki-outline ki-element-11',
        title: 'Homeworks',
        description: 'Don\'t forget to turn in your task',
        value: '207',
        total: '214',
        percentage: '92%',
        percentageClass: 'badge-light-success'
    },
    {
        icon: 'ki-outline ki-abstract-24',
        title: 'Tests',
        description: 'You take 12 subjects at this semester',
        value: '27',
        total: '38',
        percentage: '80%',
        percentageClass: 'badge-light-warning'
    }
]

// Today Homework
const todayHomework = [
    {
        task: 'Book p. 77-85, read & complete tasks 1-6 on p. 85',
        subject: 'Physics',
        status: 'Done',
        statusClass: 'badge-light-success',
        bulletClass: 'bg-success',
        checked: true
    },
    {
        task: 'Workbook p. 17, tasks 1-6',
        subject: 'Mathematics',
        status: 'In Process',
        statusClass: 'badge-light-primary',
        bulletClass: 'bg-primary',
        checked: false
    },
    {
        task: 'Learn paragraph p. 99, Exercise 1,2,3 Scoping & Estimations',
        subject: 'Chemistry',
        status: 'Done',
        statusClass: 'badge-light-success',
        bulletClass: 'bg-success',
        checked: true
    },
    {
        task: 'Write essay 1000 words "WW2 results"',
        subject: 'History',
        status: 'In Process',
        statusClass: 'badge-light-primary',
        bulletClass: 'bg-primary',
        checked: false
    },
    {
        task: 'Internal conflicts in Philip Larkin poems, read p 380-515',
        subject: 'English Language',
        status: 'In Process',
        statusClass: 'badge-light-primary',
        bulletClass: 'bg-primary',
        checked: false
    }
]

// Recent Homework
const recentHomework = [
    {
        task: 'Book p. 77-85, read & complete tasks 1-6 on p. 85',
        subject: 'Physics',
        status: 'Done',
        statusClass: 'badge-light-success',
        bulletClass: 'bg-success',
        checked: true
    },
    {
        task: 'Workbook p. 17, tasks 1-6',
        subject: 'Mathematics',
        status: 'In Process',
        statusClass: 'badge-light-primary',
        bulletClass: 'bg-primary',
        checked: false
    },
    {
        task: 'Learn paragraph p. 99, Exercise 1,2,3 Scoping & Estimations',
        subject: 'Chemistry',
        status: 'Done',
        statusClass: 'badge-light-success',
        bulletClass: 'bg-success',
        checked: true
    },
    {
        task: 'Write essay 1000 words "WW2 results"',
        subject: 'History',
        status: 'In Process',
        statusClass: 'badge-light-primary',
        bulletClass: 'bg-primary',
        checked: false
    }
]

// Recommended Courses
const recommendedCourses = [
    {
        name: 'UI/UX Design',
        courses: '40+ Courses',
        color: 'danger',
        initial: 'M'
    },
    {
        name: 'QA Analysis',
        courses: '18 Courses',
        color: 'success',
        initial: 'Q'
    },
    {
        name: 'Web Development',
        courses: '120+ Courses',
        color: 'primary',
        initial: 'W'
    },
    {
        name: 'Marketing',
        courses: '50+ Courses',
        color: 'info',
        initial: 'M'
    },
    {
        name: 'Philosophy',
        courses: '24+ Courses',
        color: 'warning',
        initial: 'P'
    }
]
</script>

<template>
    <Head :title="`SEKOLAHMU - ${user.name}`" />

    <Authenticated
        title="SMK Al Azhar Menganti Gresik"
        :breadcrumbs="['Dashboards']"
    >
        <!--begin::Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!--begin::Col - Schedule & Overview-->
            <div class="col-xxl-8">
                <!--begin::Row - Top section-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <!--begin::Col - Schedule-->
                    <div class="col-xl-8">
                        <!--begin::Table widget 8-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Today's Schedule</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">13 Jan 2026 - 11 Feb 2026</span>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Tabs-->
                                    <ul class="nav" id="kt_table_widget_8_tabs">
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4 me-1 active" data-bs-toggle="tab" href="#kt_table_widget_8_tab_1">Monday</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_8_tab_2">Tuesday</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_8_tab_3">Wednesday</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_table_widget_8_tab_4">Thursday</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-primary fw-bold px-4" data-bs-toggle="tab" href="#kt_table_widget_8_tab_5">Friday</a>
                                        </li>
                                    </ul>
                                    <!--end::Tabs-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-6">
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <!--begin::Tap pane-->
                                    <div class="tab-pane fade show active" id="kt_table_widget_8_tab_1">
                                        <!--begin::Table container-->
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table class="table align-middle">
                                                <!--begin::Table body-->
                                                <tbody>
                                                    <tr v-for="(schedule, index) in todaySchedule" :key="index">
                                                        <td class="fs-6 fw-bold text-gray-800 min-w-100px">{{ schedule.time }}</td>
                                                        <td class="fs-6 fw-bold text-gray-500">{{ schedule.lesson }}</td>
                                                        <td class="fs-6 fw-bold text-gray-500">{{ schedule.location }}</td>
                                                        <td class="pe-0 text-end min-w-75px">
                                                            <button class="btn btn-sm btn-light">Skip</button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <!--end::Table body-->
                                            </table>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Tap pane-->
                                </div>
                                <!--end::Tab Content-->
                                <!--begin::Action-->
                                <div class="d-flex justify-content-end pt-5">
                                    <a href="#" class="btn btn-sm btn-light me-2">Add Lesson</a>
                                    <a href="#" class="btn btn-sm btn-danger">Call Sick for Today</a>
                                </div>
                                <!--end::Action-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Table widget 8-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col - Help Card-->
                    <div class="col-xl-4">
                        <!--begin::Engage widget-->
                        <div class="card bg-primary h-xl-100">
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column pt-13 pb-14">
                                <!--begin::Heading-->
                                <div class="m-0">
                                    <!--begin::Title-->
                                    <h1 class="fw-semibold text-white text-center lh-lg mb-9">
                                        How are you feeling today? 
                                        <span class="fw-bolder">Here we are to Help</span>
                                    </h1>
                                    <!--end::Title-->
                                    <!--begin::Illustration-->
                                    <div class="flex-grow-1 bgi-no-repeat bgi-size-contain bgi-position-x-center card-rounded-bottom h-200px mh-200px my-5 mb-lg-12" style="background-image:url('assets/media/svg/illustrations/easy/6.svg')"></div>
                                    <!--end::Illustration-->
                                </div>
                                <!--end::Heading-->
                                <!--begin::Links-->
                                <div class="text-center">
                                    <a class="btn btn-sm btn-success me-2">Psychologist</a>
                                    <a class="btn btn-sm bg-white btn-color-white bg-opacity-20">Nurse</a>
                                </div>
                                <!--end::Links-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Engage widget-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->

                <!--begin::Row - Overview Stats-->
                <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                    <div class="col-xxl-12">
                        <!--begin::Chart widget 22-->
                        <div class="card h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header position-relative py-0 border-bottom-2">
                                <!--begin::Nav-->
                                <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                                    <li class="nav-item p-0 ms-0 me-8">
                                        <a class="nav-link btn btn-color-muted active px-0" data-bs-toggle="tab" href="#kt_chart_widget_22_tab_1">
                                            <span class="nav-text fw-semibold fs-4 mb-3">Overview</span>
                                            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item p-0 ms-0">
                                        <a class="nav-link btn btn-color-muted px-0" data-bs-toggle="tab" href="#kt_chart_widget_22_tab_2">
                                            <span class="nav-text fw-semibold fs-4 mb-3">Performance</span>
                                            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Nav-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pb-3">
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <!--begin::Tap pane-->
                                    <div class="tab-pane fade show active" id="kt_chart_widget_22_tab_1">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-wrap flex-md-nowrap">
                                            <!--begin::Items-->
                                            <div class="me-md-5 w-100">
                                                <!--begin::Item-->
                                                <div 
                                                    v-for="(stat, index) in overviewStats" 
                                                    :key="index"
                                                    class="d-flex border border-gray-300 border-dashed rounded p-6 mb-6"
                                                >
                                                    <!--begin::Block-->
                                                    <div class="d-flex align-items-center flex-grow-1 me-2 me-sm-5">
                                                        <!--begin::Symbol-->
                                                        <div class="symbol symbol-50px me-4">
                                                            <span class="symbol-label">
                                                                <i :class="[stat.icon, 'fs-2qx text-primary']"></i>
                                                            </span>
                                                        </div>
                                                        <!--end::Symbol-->
                                                        <!--begin::Section-->
                                                        <div class="me-2">
                                                            <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ stat.title }}</a>
                                                            <span class="text-gray-500 fw-bold d-block fs-7">{{ stat.description }}</span>
                                                        </div>
                                                        <!--end::Section-->
                                                    </div>
                                                    <!--end::Block-->
                                                    <!--begin::Info-->
                                                    <div class="d-flex align-items-center">
                                                        <span class="text-gray-900 fw-bolder fs-2x">{{ stat.value }}</span>
                                                        <span class="fw-semibold fs-2 text-gray-600 mx-1 pt-1">/</span>
                                                        <span class="text-gray-600 fw-semibold fs-2 me-3 pt-2">{{ stat.total }}</span>
                                                        <span class="badge badge-lg align-self-center px-2" :class="stat.percentageClass">{{ stat.percentage }}</span>
                                                    </div>
                                                    <!--end::Info-->
                                                </div>
                                                <!--end::Item-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Tap pane-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Chart widget 22-->
                    </div>
                </div>
                <!--end::Row-->

                <!--begin::Row - Today Homework & Recent-->
                <div class="row g-5 g-xl-10">
                    <div class="col-xxl-12">
                        <!--begin::Timeline widget 2-->
                        <div class="card h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header position-relative py-0 border-bottom-2">
                                <!--begin::Nav-->
                                <ul class="nav nav-stretch nav-pills nav-pills-custom d-flex mt-3">
                                    <li class="nav-item p-0 ms-0 me-8">
                                        <a class="nav-link btn btn-color-muted active px-0" data-bs-toggle="pill" href="#kt_timeline_widget_2_tab_1">
                                            <span class="nav-text fw-semibold fs-4 mb-3">Today Homeworks</span>
                                            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item p-0 ms-0 me-8">
                                        <a class="nav-link btn btn-color-muted px-0" data-bs-toggle="pill" href="#kt_timeline_widget_2_tab_2">
                                            <span class="nav-text fw-semibold fs-4 mb-3">Recent</span>
                                            <span class="bullet-custom position-absolute z-index-2 w-100 h-2px top-100 bottom-n100 bg-primary rounded"></span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Nav-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body">
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <!--begin::Tab pane - Today Homework-->
                                    <div class="tab-pane fade show active" id="kt_timeline_widget_2_tab_1">
                                        <div class="table-responsive">
                                            <table class="table align-middle gs-0 gy-4">
                                                <thead>
                                                    <tr>
                                                        <th class="p-0 w-10px"></th>
                                                        <th class="p-0 w-25px"></th>
                                                        <th class="p-0 min-w-400px"></th>
                                                        <th class="p-0 min-w-100px"></th>
                                                        <th class="p-0 min-w-125px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in todayHomework" :key="index">
                                                        <td>
                                                            <span class="bullet bullet-vertical d-flex align-items-center h-40px" :class="item.bulletClass"></span>
                                                        </td>
                                                        <td class="ps-0">
                                                            <div class="form-check form-check-custom form-check-solid" :class="item.checked ? 'form-check-success' : ''">
                                                                <input class="form-check-input" type="checkbox" :checked="item.checked" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">{{ item.task }}</a>
                                                            <span class="text-gray-500 fw-bold fs-7 d-block">{{ item.subject }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="badge" :class="item.statusClass">{{ item.status }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                                <a href="#" class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                    <i class="ki-outline ki-printer fs-3"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                    <i class="ki-outline ki-sms fs-3"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                    <i class="ki-outline ki-paper-clip fs-3"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!--end::Tab pane-->
                                    <!--begin::Tab pane - Recent-->
                                    <div class="tab-pane fade" id="kt_timeline_widget_2_tab_2">
                                        <div class="table-responsive">
                                            <table class="table align-middle gs-0 gy-4">
                                                <thead>
                                                    <tr>
                                                        <th class="p-0 w-10px"></th>
                                                        <th class="p-0 w-25px"></th>
                                                        <th class="p-0 min-w-400px"></th>
                                                        <th class="p-0 min-w-100px"></th>
                                                        <th class="p-0 min-w-125px"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in recentHomework" :key="index">
                                                        <td>
                                                            <span class="bullet bullet-vertical d-flex align-items-center h-40px" :class="item.bulletClass"></span>
                                                        </td>
                                                        <td class="ps-0">
                                                            <div class="form-check form-check-custom form-check-solid" :class="item.checked ? 'form-check-success' : ''">
                                                                <input class="form-check-input" type="checkbox" :checked="item.checked" />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="text-gray-800 text-hover-primary fw-bold fs-6">{{ item.task }}</a>
                                                            <span class="text-gray-500 fw-bold fs-7 d-block">{{ item.subject }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <span class="badge" :class="item.statusClass">{{ item.status }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex justify-content-end flex-shrink-0">
                                                                <a href="#" class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                    <i class="ki-outline ki-printer fs-3"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                    <i class="ki-outline ki-sms fs-3"></i>
                                                                </a>
                                                                <a href="#" class="btn btn-icon btn-color-muted btn-bg-light btn-active-color-primary btn-sm">
                                                                    <i class="ki-outline ki-paper-clip fs-3"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!--end::Tab pane-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Timeline widget 2-->
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Col-->

            <!--begin::Col - Sidebar Right-->
            <div class="col-xxl-4">
                <!--begin::Recommended Courses-->
                <div class="card mb-5 mb-xl-10">
                    <!--begin::Header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="fw-bold m-0">Recommended for you</h3>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-5">
                        <!--begin::Item-->
                        <div 
                            v-for="(course, index) in recommendedCourses" 
                            :key="index"
                            class="d-flex mb-7"
                        >
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label" :class="`bg-light-${course.color}`">
                                    <span :class="`text-${course.color}`" class="fs-3 fw-bold">{{ course.initial }}</span>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">{{ course.name }}</a>
                                <span class="text-muted d-block fw-semibold">{{ course.courses }}</span>
                            </div>
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Recommended Courses-->
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </Authenticated>
</template>