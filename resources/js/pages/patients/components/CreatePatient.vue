<script setup lang="ts">
import PrecognitiveForm from '@/components/precognitive-form/PrecognitiveForm.vue';
import PrecognitiveInput from '@/components/precognitive-form/PrecognitiveInput.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Separator } from '@/components/ui/separator';
import { Patient, SharedData } from '@/types';
import { Page } from '@inertiajs/core';
import { useForm } from 'laravel-precognition-vue-inertia';
import { LoaderCircle, Mail, Phone } from 'lucide-vue-next';
import { DialogPortal } from 'reka-ui';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const open = ref(false);

const form = useForm<Partial<Patient>>('post', route('patients.store'), {
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
});

const onDialogStateChange = (state: boolean) => {
    if (state) return;

    form.reset();
    form.clearErrors();
};

const onSubmit = () => {
    form.submit({
        onSuccess: (params) => toast.success((params as Page<SharedData>).props.flash.success ?? 'Paziente creato con successo.'),
    });
};
</script>

<template>
    <Dialog v-model:open="open" @update:open="onDialogStateChange">
        <DialogTrigger as-child>
            <Button> Aggiungi Paziente</Button>
        </DialogTrigger>

        <DialogPortal>
            <DialogContent class="sm:max-w-2xl" @interact-outside="(event) => event.preventDefault()">
                <DialogHeader>
                    <DialogTitle> Aggiungi Paziente</DialogTitle>
                    <DialogDescription> Cra un nuovo paziente.</DialogDescription>
                </DialogHeader>

                <PrecognitiveForm :form id="create-form" @submit="onSubmit" class="grid grid-cols-1 gap-2 md:grid-cols-2">
                    <PrecognitiveInput name="first_name" label="Nome" v-model.ucfirst="form.first_name" placeholder="Nome" required />
                    <PrecognitiveInput name="last_name" label="Cognome" v-model.ucfirst="form.last_name" placeholder="Cognome" required />
                    <PrecognitiveInput name="email" label="Email" v-model="form.email" placeholder="Email" :icon="Mail" />
                    <PrecognitiveInput
                        name="phone"
                        label="Numero di telefono"
                        v-model.ucfirst="form.phone"
                        placeholder="Numero di telefono"
                        :icon="Phone"
                    />
                </PrecognitiveForm>

                <Separator />

                <DialogFooter>
                    <DialogClose as-child>
                        <Button type="button" variant="outline">Annulla</Button>
                    </DialogClose>
                    <Button form="create-form" type="submit" :disabled="form.processing || form.hasErrors || !form.isDirty">
                        <LoaderCircle class="animate-spin" v-if="form.processing" />
                        Salva
                    </Button>
                </DialogFooter>
            </DialogContent>
        </DialogPortal>
    </Dialog>
</template>
