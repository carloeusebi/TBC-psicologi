<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{
    genders: Array<{ key: string; label: string }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pazienti',
        href: route('patients.index'),
    },
    {
        title: 'Crea Paziente',
        href: route('patients.create'),
    },
];
</script>

<template>
    <AppLayout :breadcrumbs>
        <Head title="Crea Paziente" />

        {{ genders }}
    </AppLayout>
</template>
