<script setup lang="ts">
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import PatientInfo from '@/pages/patients/components/PatientInfo.vue';
import { BreadcrumbItem, Patient } from '@/types';
import { Head } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { computed, ref } from 'vue';

type Tab = 'profile' | 'evaluations' | 'invoices' | 'documents';

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
        href: route('patients.show', { patient: patient.id }),
    },
];

const activeTab = ref<Tab>('profile');

const patientInfoRef = ref<InstanceType<typeof PatientInfo> | null>(null);

const tabsDisabled = computed(() => patientInfoRef.value?.form.isDirty);
</script>

<template>
    <AppLayout :breadcrumbs>
        <Head :title="patient.name" />

        <div class="flex-1 flex-col space-y-8 p-2 md:flex md:p-4 lg:p-8">
            <div class="items-center justify-between space-y-2 md:flex">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">{{ patient.name }}</h2>
                    <div class="text-sm text-muted-foreground">
                        <p>Paziente dal {{ format(patient.therapy_start_date, 'd MMMM y') }}.</p>
                        <p>{{ patient.evaluations_count ?? 0 }} valutazioni completate.</p>
                    </div>
                </div>
            </div>

            <Tabs default-value="profile" v-model="activeTab">
                <TabsList>
                    <TabsTrigger value="profile" :disabled="tabsDisabled">Profilo</TabsTrigger>
                    <TabsTrigger value="evaluations" :disabled="tabsDisabled">Valutazioni</TabsTrigger>
                    <TabsTrigger value="invoices" :disabled="tabsDisabled">Fatture</TabsTrigger>
                    <TabsTrigger value="documents" :disabled="tabsDisabled">Documenti</TabsTrigger>
                </TabsList>

                <TabsContent value="profile">
                    <PatientInfo ref="patientInfoRef" :patient :genders />
                </TabsContent>
                <TabsContent value="evaluations">
                    <div>Valutazioni</div>
                </TabsContent>
                <TabsContent value="invoices">
                    <div>Fatture</div>
                </TabsContent>
                <TabsContent value="documents">
                    <div>Documenti</div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
