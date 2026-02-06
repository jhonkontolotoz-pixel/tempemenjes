<script setup>
import Authenticated from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <Head :title="`Dashboard - ${page.props.auth.user.name}`" />

    <Authenticated>
        <div class="card">
            <div class="card-body">
                Ini isi dashboard kamu
            </div>
        </div>
    </Authenticated>
</template>
a