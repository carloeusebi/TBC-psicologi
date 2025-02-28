<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, Patient } from '@/types';
import { Head } from '@inertiajs/vue3';

const { patient } = defineProps<{
    patient: Patient;
    genders: Array<{ key: string; label: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pazienti',
        href: route('patients.index'),
    },
    {
        title: patient.name,
        href: route('patients.show', patient.id),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Head :title="patient.name" />
        <pre>{{ patient }}</pre>
    </AppLayout>
</template>
