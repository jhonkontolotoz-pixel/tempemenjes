<script setup>
import Header from '@/Pages/Dashboards/Layouts/Header.vue'
import { Head, usePage, Link } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

const latestArticles = [
    {
        title: 'Strategi Konten 2026',
        desc: 'Cara membangun blog yang konsisten dan scalable.',
        date: '08 Feb 2026'
    },
    {
        title: 'SEO Modern untuk Developer',
        desc: 'Optimasi teknis agar blog cepat dan ranking naik.',
        date: '05 Feb 2026'
    },
    {
        title: 'UX Writing dalam Blog',
        desc: 'Meningkatkan engagement lewat struktur tulisan.',
        date: '01 Feb 2026'
    }
]

const news = [
    {
        title: 'Google Update Algoritma',
        badge: 'Important',
        badgeClass: 'badge-light-danger'
    },
    {
        title: 'Laravel 12 Resmi Dirilis',
        badge: 'New',
        badgeClass: 'badge-light-success'
    }
]

const updates = [
    'Perbaikan performa halaman blog',
    'Optimasi loading image',
    'Penambahan fitur kategori',
    'Fix bug pagination'
]
</script>

<template>
    <Head :title="`INILAHGUA - ${user.name}`" />

    <Header
        title="InfoneBOS"
        :breadcrumbs="['Dashboard', 'Blog']"
    >
        <!-- HEADER ACTION -->
        <template #actions>
            <Link href="#" class="btn btn-primary">
                Create New
            </Link>
        </template>

        <!-- ===================== -->
        <!-- DASHBOARD -->
        <!-- ===================== -->
        <div class="card card-flush mb-10">
            <div class="card-header">
                <div class="card-title">
                    <h2 class="fw-bold">Halo icibos</h2>
                </div>
            </div>

            <div class="card-body pt-0">
                <p class="fs-6 text-gray-600 mb-0">
                    Selamat datang,
                    <span class="fw-bold text-gray-900">
                        {{ user.name }}
                    </span>
                </p>
            </div>
        </div>

        <!-- ===================== -->
        <!-- LATEST ARTICLES -->
        <!-- ===================== -->
        <div class="card mb-10">
            <div class="card-header">
                <h3 class="card-title fw-bold">Latest Articles</h3>
            </div>

            <div class="card-body">
                <div class="row g-6">
                    <div
                        v-for="(article, index) in latestArticles"
                        :key="index"
                        class="col-md-4"
                    >
                        <div class="card border-hover-primary h-100">
                            <div class="card-body">
                                <h4 class="fw-bold text-gray-900 mb-2">
                                    {{ article.title }}
                                </h4>

                                <div class="text-gray-600 fs-6 mb-4">
                                    {{ article.desc }}
                                </div>

                                <div class="text-gray-500 fs-8">
                                    {{ article.date }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== -->
        <!-- NEWS -->
        <!-- ===================== -->
        <div class="card mb-10">
            <div class="card-header">
                <h3 class="card-title fw-bold">News</h3>
            </div>

            <div class="card-body">
                <div
                    v-for="(item, index) in news"
                    :key="index"
                    class="d-flex justify-content-between align-items-center mb-4"
                >
                    <span class="fw-semibold text-gray-800">
                        {{ item.title }}
                    </span>

                    <span :class="['badge', item.badgeClass]">
                        {{ item.badge }}
                    </span>
                </div>
            </div>
        </div>

        <!-- ===================== -->
        <!-- UPDATES -->
        <!-- ===================== -->
        <div class="card mb-10">
            <div class="card-header">
                <h3 class="card-title fw-bold">Updates</h3>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li
                        v-for="(update, index) in updates"
                        :key="index"
                        class="list-group-item border-0 ps-0 text-gray-700 fs-6"
                    >
                        <i class="ki-outline ki-check-circle text-success me-2"></i>
                        {{ update }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- ===================== -->
        <!-- DEVELOPER INFO -->
        <!-- ===================== -->
        <div class="card mb-10">
            <div class="card-header">
                <h3 class="card-title fw-bold">Developer Info</h3>
            </div>

            <div class="card-body">
                <div class="row g-6">
                    <div class="col-md-6">
                        <div class="card border-hover-dark h-100">
                            <div class="card-body">
                                <div class="fw-bold text-gray-900 mb-2">
                                    GitHub
                                </div>
                                <div class="text-gray-600 fs-6">
                                    Username:
                                    <span class="fw-semibold">
                                        jhonkontolotoz
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-hover-danger h-100">
                            <div class="card-body">
                                <div class="fw-bold text-gray-900 mb-2">
                                    Instagram
                                </div>
                                <div class="text-gray-600 fs-6">
                                    Username:
                                    <span class="fw-semibold">
                                        jhonkontolotozofficial
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== -->
        <!-- EDUKASI TEKNOLOGI -->
        <!-- ===================== -->
        <div class="card mb-15">
            <div class="card-header">
                <h3 class="card-title fw-bold">
                    Tentang Teknologi yang Digunakan
                </h3>
            </div>

            <div class="card-body">

    <h4 class="fw-bold text-primary mb-2">Laravel</h4>
    <p class="text-gray-700 fs-6 mb-6">
        Laravel adalah framework PHP berbasis MVC yang
        mempermudah pengembangan aplikasi web melalui
        routing, ORM Eloquent, middleware, dan sistem
        authentication yang terstruktur.
    </p>

    <h4 class="fw-bold text-success mb-2">Vue.js</h4>
    <p class="text-gray-700 fs-6 mb-6">
        Vue.js adalah framework JavaScript progresif untuk
        membangun antarmuka interaktif menggunakan sistem
        reactive data dan arsitektur berbasis komponen.
    </p>

    <h4 class="fw-bold text-warning mb-2">Inertia.js</h4>
    <p class="text-gray-700 fs-6 mb-6">
        Inertia menghubungkan Laravel dengan Vue tanpa API
        terpisah sehingga aplikasi terasa seperti SPA namun
        tetap server-driven.
    </p>

    <h4 class="fw-bold text-info mb-2">Bootstrap</h4>
    <p class="text-gray-700 fs-6 mb-6">
        Bootstrap adalah framework CSS untuk membuat UI
        responsif menggunakan grid system dan komponen siap pakai.
    </p>

    <h4 class="fw-bold text-dark mb-2">Metronic 8 Template</h4>
    <p class="text-gray-700 fs-6 mb-0">
        Metronic 8 adalah admin dashboard template premium berbasis Bootstrap
        yang menyediakan layout, komponen UI, sistem sidebar, header sticky,
        serta utilitas JavaScript siap pakai. Template ini mempercepat proses
        development karena struktur halaman, styling, dan interaksi sudah
        terstandarisasi dan modular.
    </p>

     </div>
</div>
    </Header>
</template>