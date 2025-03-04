<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import CreatePatient from '@/pages/patients/components/CreatePatient.vue';
import { BreadcrumbItem, Patient } from '@/types';
import { Head } from '@inertiajs/vue3';
import { columns } from './components/table/columns';
import DataTable from './components/table/DataTable.vue';

defineProps<{
    patients: Array<Patient>;
    genders: Array<{ value: string; label: string }>;
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
        <div class="h-full max-w-[100vw] flex-1 flex-col space-y-8 p-2 md:flex md:p-4 lg:p-8">
            <div class="items-center justify-between space-y-2 md:flex">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Pazienti</h2>
                    <p class="text-muted-foreground">Ecco una lista dei pazienti attivi</p>
                </div>
                <div class="flex items-center space-x-2">
                    <CreatePatient />
                </div>
            </div>
            <DataTable :data="patients" :columns="columns" />
        </div>
    </AppLayout>
</template>
