<script setup>
import Authenticated from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

const page = usePage()

// Data untuk statistics cards
const stats = ref([
    { title: 'Total Orders', value: '5,000', icon: 'ki-chart-simple-3', color: 'primary' },
    { title: 'New Customers', value: '1,250', icon: 'ki-profile-user', color: 'danger' },
    { title: 'Total Revenue', value: '$125,000', icon: 'ki-dollar', color: 'success' },
    { title: 'Pending Tasks', value: '99', icon: 'ki-abstract-26', color: 'warning' }
])

// Data untuk recent activities
const activities = ref([
    {
        title: 'Order Completed',
        subtitle: 'Order #12345',
        user: 'John Doe',
        userRole: 'Customer',
        time: '2 mins ago',
        icon: 'ki-check',
        iconColor: 'success'
    },
    {
        title: 'New Message',
        subtitle: 'From Support Team',
        user: 'Admin',
        userRole: 'System',
        time: '15 mins ago',
        icon: 'ki-notification',
        iconColor: 'warning'
    },
    {
        title: 'New User Registered',
        subtitle: 'User ID: #8765',
        user: 'Jane Smith',
        userRole: 'New Customer',
        time: '1 hour ago',
        icon: 'ki-user',
        iconColor: 'primary'
    },
    {
        title: 'Payment Failed',
        subtitle: 'Transaction #9876',
        user: 'Bob Wilson',
        userRole: 'Customer',
        time: '3 hours ago',
        icon: 'ki-cross',
        iconColor: 'danger'
    },
    {
        title: 'Report Generated',
        subtitle: 'Monthly Report',
        user: 'System',
        userRole: 'Automated',
        time: '5 hours ago',
        icon: 'ki-file',
        iconColor: 'info'
    }
])

// Data untuk top products
const topProducts = ref([
    { name: 'Product A', category: 'Electronics', sales: '250 sales', icon: 'ki-laptop', color: 'primary' },
    { name: 'Product B', category: 'Accessories', sales: '180 sales', icon: 'ki-watch', color: 'danger' },
    { name: 'Product C', category: 'Fashion', sales: '145 sales', icon: 'ki-briefcase', color: 'warning' }
])

// Data untuk quick stats
const quickStats = ref([
    { label: 'New Orders', value: '425', trend: 'up' },
    { label: 'Revenue', value: '$12,500', trend: 'up' },
    { label: 'New Users', value: '89', trend: 'up' },
    { label: 'Pending Tasks', value: '15', trend: 'down' },
    { label: 'Support Tickets', value: '32', trend: 'up' }
])

// Initialize charts
onMounted(() => {
    initSalesChart()
    initTaskProgressChart()
})

// Sales Overview Chart
const initSalesChart = () => {
    const chartElement = document.getElementById('kt_charts_widget_1_chart')
    if (!chartElement) return

    const options = {
        series: [{
            name: 'Sales',
            data: [30, 45, 32, 70, 40, 40]
        }],
        chart: {
            fontFamily: 'inherit',
            type: 'area',
            height: 350,
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0,
                stops: [0, 80, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            show: true,
            width: 3,
            colors: ['#3E97FF']
        },
        xaxis: {
            categories: ['Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb'],
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: {
                    colors: '#A1A5B7',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#A1A5B7',
                    fontSize: '12px'
                },
                formatter: (value) => '$' + value + 'K'
            }
        },
        tooltip: {
            y: {
                formatter: (value) => '$' + value + 'K'
            }
        },
        colors: ['#3E97FF'],
        grid: {
            borderColor: '#E4E6EF',
            strokeDashArray: 4
        }
    }

    const chart = new ApexCharts(chartElement, options)
    chart.render()
}

// Task Progress Chart
const initTaskProgressChart = () => {
    const chartElement = document.getElementById('kt_charts_widget_3_chart')
    if (!chartElement) return

    const options = {
        series: [{
            name: 'Progress',
            data: [30, 45, 32, 70, 40]
        }],
        chart: {
            fontFamily: 'inherit',
            type: 'area',
            height: 100,
            toolbar: { show: false },
            zoom: { enabled: false },
            sparkline: { enabled: true }
        },
        dataLabels: { enabled: false },
        fill: { type: 'solid', opacity: 1 },
        stroke: {
            curve: 'smooth',
            show: true,
            width: 3,
            colors: ['#3E97FF']
        },
        xaxis: {
            categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            labels: { show: false }
        },
        yaxis: {
            min: 0,
            max: 80,
            labels: { show: false }
        },
        tooltip: {
            y: {
                formatter: (value) => value + '%'
            }
        },
        colors: ['#3E97FF']
    }

    const chart = new ApexCharts(chartElement, options)
    chart.render()
}
</script>

<template>
    <Head :title="`Dashboard - ${page.props.auth.user.name}`" />

    <Authenticated>
        <!--begin::Welcome Message-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body p-9">
                <h1 class="fw-bold text-gray-900">Selamat datang bosss</h1>
                <p class="text-gray-600 fs-5 mb-0">Have a great day ahead!</p>
            </div>
        </div>
        <!--end::Welcome Message-->

        <!--begin::Statistics Cards-->
        <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
            <div v-for="(stat, index) in stats" :key="index" class="col-xl-3">
                <div :class="`card bg-${stat.color} hoverable card-xl-stretch mb-xl-8`">
                    <div class="card-body">
                        <i :class="`ki-outline ${stat.icon} fs-2x text-white`"></i>
                        <div class="text-white fw-bold fs-2 mb-2 mt-5">{{ stat.value }}</div>
                        <div class="fw-semibold text-white">{{ stat.title }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Statistics Cards-->

        <!--begin::Charts Row-->
        <div class="row g-5 g-xl-8">
            <!--begin::Sales Chart-->
            <div class="col-xl-6">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Sales Overview</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Last 6 months performance</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div id="kt_charts_widget_1_chart" style="height: 350px"></div>
                    </div>
                </div>
            </div>
            <!--end::Sales Chart-->

            <!--begin::Recent Activities-->
            <div class="col-xl-6">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Recent Activities</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Latest updates</span>
                        </h3>
                    </div>
                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Activity</th>
                                        <th class="min-w-140px">User</th>
                                        <th class="min-w-120px">Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(activity, index) in activities" :key="index">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-5">
                                                    <span :class="`symbol-label bg-light-${activity.iconColor}`">
                                                        <i :class="`ki-outline ${activity.icon} fs-2x text-${activity.iconColor}`"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-start flex-column">
                                                    <a href="#" class="text-dark fw-bold text-hover-primary fs-6">{{ activity.title }}</a>
                                                    <span class="text-muted fw-semibold text-muted d-block fs-7">{{ activity.subtitle }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="text-dark fw-bold text-hover-primary d-block fs-6">{{ activity.user }}</a>
                                            <span class="text-muted fw-semibold text-muted d-block fs-7">{{ activity.userRole }}</span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-bold d-block fs-6">{{ activity.time }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Recent Activities-->
        </div>
        <!--end::Charts Row-->

        <!--begin::Bottom Row-->
        <div class="row g-5 g-xl-8">
            <!--begin::Task Progress-->
            <div class="col-xl-4">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-body p-0 d-flex justify-content-between flex-column overflow-hidden">
                        <div class="d-flex flex-stack flex-wrap flex-grow-1 px-9 pt-9 pb-3">
                            <div class="me-2">
                                <span class="fw-bold text-gray-800 d-block fs-3">Tasks Progress</span>
                                <span class="text-gray-400 fw-semibold">Completed: 45 of 99</span>
                            </div>
                            <div class="fw-bold fs-3 text-primary">45%</div>
                        </div>
                        <div id="kt_charts_widget_3_chart" style="height: 100px"></div>
                    </div>
                </div>
            </div>
            <!--end::Task Progress-->

            <!--begin::Top Products-->
            <div class="col-xl-4">
                <div class="card card-xl-stretch mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Top Products</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Best selling items</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div v-for="(product, index) in topProducts" :key="index" :class="['d-flex align-items-sm-center', index < topProducts.length - 1 ? 'mb-7' : '']">
                            <div class="symbol symbol-50px me-5">
                                <span class="symbol-label">
                                    <i :class="`ki-outline ${product.icon} fs-2x text-${product.color}`"></i>
                                </span>
                            </div>
                            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                                <div class="flex-grow-1 me-2">
                                    <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ product.name }}</a>
                                    <span class="text-muted fw-semibold d-block fs-7">{{ product.category }}</span>
                                </div>
                                <span class="badge badge-light-success fs-8 fw-bold">{{ product.sales }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Top Products-->

            <!--begin::Quick Stats-->
            <div class="col-xl-4">
                <div class="card card-xl-stretch mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-dark">Quick Stats</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">This week</span>
                        </h3>
                    </div>
                    <div class="card-body pt-6">
                        <template v-for="(stat, index) in quickStats" :key="index">
                            <div class="d-flex flex-stack">
                                <div class="text-gray-700 fw-semibold fs-6 me-2">{{ stat.label }}</div>
                                <div class="d-flex align-items-senter">
                                    <i :class="`ki-outline ${stat.trend === 'up' ? 'ki-arrow-up' : 'ki-arrow-down'} fs-5 text-${stat.trend === 'up' ? 'success' : 'danger'} me-1`"></i>
                                    <div class="fw-bold fs-6 text-gray-800">{{ stat.value }}</div>
                                </div>
                            </div>
                            <div v-if="index < quickStats.length - 1" class="separator separator-dashed my-3"></div>
                        </template>
                    </div>
                </div>
            </div>
            <!--end::Quick Stats-->
        </div>
        <!--end::Bottom Row-->
    </Authenticated>
</template>

<style scoped>
/* Custom styles jika diperlukan */
.hoverable {
    transition: transform 0.3s ease;
}

.hoverable:hover {
    transform: translateY(-5px);
}
</style>