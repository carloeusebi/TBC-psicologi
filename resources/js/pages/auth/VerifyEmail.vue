<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <AuthLayout
        title="Verifica Email"
        description="Per favore verifica l'indirizzo email cliccando sul link che ti abbiamo appena inviato via email."
    >
        <Head title="Verifica Email" />

        <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
            Un nuovo link di verifica è stato inviato all'indirizzo email che hai fornito durante la registrazione.
        </div>

        <form @submit.prevent="submit" class="space-y-6 text-center">
            <Button :disabled="form.processing" variant="secondary">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                Richiedi una nuova email di verifica
            </Button>
            <div class="flex">
                <TextLink :href="route('profile.edit')" as="button" class="mx-auto block text-sm">Profilo</TextLink>
                <TextLink :href="route('logout')" method="post" as="button" class="mx-auto block text-sm">Esci</TextLink>
            </div>
        </form>
    </AuthLayout>
</template>
