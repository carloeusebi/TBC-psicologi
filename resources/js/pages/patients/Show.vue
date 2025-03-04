<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import PatientInfo from '@/pages/patients/components/PatientInfo.vue';
import { BreadcrumbItem, Patient } from '@/types';
import { Head } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { ChevronDown } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { actions } from './actions';

type Tab = 'profile' | 'evaluations' | 'invoices' | 'documents';

const { patient } = defineProps<{
    patient: Patient;
    genders: Array<{ value: string; label: string }>;
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

                <div>
                    <DropdownMenu :modal="false">
                        <DropdownMenuTrigger as-child>
                            <Button variant="secondary" class="w-full" id="actions-button">
                                <ChevronDown class="mr-2 size-4" />
                                Azioni
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <template v-for="(actionGroup, index) in actions" :key="index">
                                <template v-for="action in actionGroup" :key="action.label">
                                    <DropdownMenuItem @click="action.onClick(patient)" :class="action.class">
                                        <Component v-if="action.icon" :is="action.icon" class="mr-2 size-4" />
                                        {{ action.label }}
                                    </DropdownMenuItem>
                                </template>
                                <DropdownMenuSeparator v-if="index < actions.length - 1" />
                            </template>
                        </DropdownMenuContent>
                    </DropdownMenu>
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
