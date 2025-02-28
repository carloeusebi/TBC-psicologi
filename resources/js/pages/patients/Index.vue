<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, LaravelPaginatedResponse, Patient } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    patients: LaravelPaginatedResponse<Patient>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pazienti',
        href: route('patients.index'),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Head title="Pazienti" />
        <div v-for="patient in patients.data" :key="patient.id">
            <Link :href="route('patients.show', patient.id)">
                <pre>{{ patient.name }}</pre>
            </Link>
        </div>
    </AppLayout>
</template>
