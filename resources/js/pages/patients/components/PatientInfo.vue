<script setup lang="ts">
import ClipboardButton from '@/components/CopyButton.vue';
import FormToast from '@/components/FormToast.vue';
import PrecognitiveDatePicker from '@/components/precognitive-form/PrecognitiveDatePicker.vue';
import PrecognitiveForm from '@/components/precognitive-form/PrecognitiveForm.vue';
import PrecognitiveInput from '@/components/precognitive-form/PrecognitiveInput.vue';
import PrecognitiveSelect from '@/components/precognitive-form/PrecognitiveSelect.vue';
import PrecognitiveTagsInput from '@/components/precognitive-form/PrecognitiveTagsInput.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Patient, SharedData } from '@/types';
import { Page } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { getLocalTimeZone, today } from '@internationalized/date';
import { format, formatDistanceToNow } from 'date-fns';
import { useForm } from 'laravel-precognition-vue-inertia';
import {
    BriefcaseBusiness,
    Contact,
    Database,
    GraduationCap,
    Hospital,
    Mail,
    MapPinHouse,
    NotebookTabs,
    Phone,
    PillBottle,
    Ruler,
    UsersRound,
    Weight,
} from 'lucide-vue-next';
import { h, onBeforeUnmount, watch } from 'vue';
import { toast } from 'vue-sonner';

const { patient } = defineProps<{
    patient: Patient;
    genders: Array<{ value: string; label: string }>;
}>();

const form = useForm<Patient>('put', route('patients.update', { patient: patient.id }), { ...patient });

let resolvePromise: (value: string) => void;
let rejectPromise: (reason?: any) => void;

const onFormSubmit = () => {
    if (form.hasErrors) return;

    const promise = new Promise<string>((resolve, reject) => {
        resolvePromise = resolve;
        rejectPromise = reject;
    });

    form.submit({
        preserveScroll: true,
        onStart: () => {
            dismissFormToast();
            toast.promise(promise, {
                loading: 'Salvataggio in corso...',
                success: (message: string) => message,
                error: 'Errore durante il salvataggio.',
            });
        },
        onSuccess: (params) => resolvePromise((params as Page<SharedData>).props.flash.success as string),
        onError: () => rejectPromise(),
    });
};

const dismissFormToast = () => toast.dismiss('form-toast');

watch(
    () => form.isDirty,
    (isDirty) => {
        dismissFormToast();
        if (!isDirty) return;
        toast(h(FormToast, { form, onSubmit: onFormSubmit }), {
            position: 'bottom-center',
            duration: 999999,
            class: '!bg-black !text-white dirty-toast',
            id: 'form-toast',
        });
    },
);

const shakeElement = (el: HTMLElement | null) => {
    if (!el) return;
    el.classList.add('animate-shake');
    el.addEventListener('animationend', () => {
        el.classList.remove('animate-shake');
    });
};

const removeRouterListener = router.on('before', (e) => {
    if (!form.isDirty) return true;
    if (e.detail.visit.method !== 'get') return true;

    if (!e.detail.visit.prefetch) {
        shakeElement(document.querySelector('.dirty-toast'));
    }

    return false;
});

onBeforeUnmount(() => {
    dismissFormToast();
    removeRouterListener();
});

defineExpose({ form });
</script>

<template>
    <PrecognitiveForm :form class="grid grid-cols-1 gap-4 md:grid-cols-2" @submit="onFormSubmit">
        <div class="space-y-4">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Contact class="h-5 w-5" />
                        Anagrafica
                    </CardTitle>
                    <CardDescription>Anagrafica del paziente</CardDescription>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <PrecognitiveInput name="first_name" label="Nome" v-model.ucfirst="form.first_name" />
                    <PrecognitiveInput name="last_name" label="Cognome" v-model.ucfirst="form.last_name" />
                    <PrecognitiveDatePicker
                        name="birth_date"
                        label="Data di Nascita"
                        v-model="form.birth_date"
                        :max-value="today(getLocalTimeZone())"
                    />
                    <PrecognitiveInput name="birth_place" label="Luogo di Nascita" v-model="form.birth_place" />
                    <PrecognitiveSelect v-model="form.gender" name="gender" :options="genders" placeholder="Seleziona il sesso" label="Sesso" />
                    <PrecognitiveInput name="codice_fiscale" label="Codice Fiscale" v-model.uppercase="form.codice_fiscale" />
                </CardContent>
            </Card>
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Hospital class="h-5 w-5" />
                        Clinica
                    </CardTitle>
                    <CardDescription> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. </CardDescription>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <PrecognitiveDatePicker
                        name="therapy_start_date"
                        v-model="form.therapy_start_date"
                        label="Data Inizio Terapia"
                        :max-value="today(getLocalTimeZone())"
                    />
                    <PrecognitiveInput name="education" v-model="form.education" label="Istruzione" :icon="GraduationCap" />
                    <PrecognitiveInput name="job" v-model="form.job" label="Lavoro" :icon="BriefcaseBusiness" />
                    <PrecognitiveTagsInput
                        name="cohabitants"
                        v-model="form.cohabitants"
                        label="Conviventi"
                        :icon="UsersRound"
                        class="md:col-span-3"
                    />
                    <PrecognitiveTagsInput class="md:col-span-3" name="drugs" v-model="form.drugs" label="Farmaci" :icon="PillBottle" />
                    <PrecognitiveInput name="height" v-model="form.height" label="Altezza(cm)" :icon="Ruler" />
                    <PrecognitiveInput name="weight" v-model="form.weight" label="Peso(kg)" :icon="Weight" />
                </CardContent>
            </Card>
        </div>

        <div class="space-y-4">
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <NotebookTabs class="h-5 w-5" />
                        Contatti
                    </CardTitle>
                    <CardDescription>Contatti e recapiti</CardDescription>
                </CardHeader>
                <CardContent>
                    <PrecognitiveInput name="phone" label="Telefono" v-model="form.phone" :icon="Phone" />
                    <PrecognitiveInput name="email" label="Email" v-model="form.email" :icon="Mail" />
                    <PrecognitiveInput name="address" label="Indirizzo" v-model="form.address" :icon="MapPinHouse" />
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Database class="h-5 w-5" />
                        Record
                    </CardTitle>
                    <CardDescription> Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos. </CardDescription>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-2 md:grid-cols-3">
                    <div class="md:col-span-3">
                        <Label>ID</Label>
                        <div class="flex items-center text-sm text-muted-foreground">
                            {{ patient.id }}
                            <ClipboardButton :source="patient.id" />
                        </div>
                    </div>
                    <div>
                        <Label>Creato</Label>
                        <div class="text-sm text-muted-foreground">
                            {{ formatDistanceToNow(patient.created_at, { addSuffix: true }) }}
                            <span class="text-xs">({{ format(patient.created_at, 'd MMMM y') }})</span>
                        </div>
                    </div>
                    <div>
                        <Label>Ultima modifica</Label>
                        <div class="text-sm text-muted-foreground">
                            <span v-if="patient.updated_at !== patient.created_at">
                                {{ formatDistanceToNow(patient.updated_at, { addSuffix: true }) }}
                                <span class="text-xs">({{ format(patient.updated_at, 'd MMMM y') }})</span>
                            </span>
                            <span v-else>-</span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <button type="submit" class="hidden" />
    </PrecognitiveForm>
</template>
