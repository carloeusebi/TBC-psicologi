<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form } from 'laravel-precognition-vue-inertia';
import { TriangleAlert } from 'lucide-vue-next';

const props = withDefaults(
    defineProps<{
        message?: string;
        form: Form<any>;
        resetLabel?: string;
        saveLabel?: string;
        onSubmit?: () => void;
    }>(),
    {
        message: 'Ci sono delle modifiche non salvate',
        resetLabel: 'Reset',
        saveLabel: 'Salva',
    },
);

const onSubmit = () => {
    if (props.onSubmit) {
        props.onSubmit();
    } else {
        props.form.submit();
    }
};

const onReset = () => {
    props.form.reset();
    props.form.clearErrors();
};
</script>

<template>
    <div class="flex items-center gap-2">
        <TriangleAlert class="h-5 w-5 text-yellow-500" />
        <p v-html="message" class="flex-1" />
        <Button size="sm" @click="onReset">{{ resetLabel }}</Button>
        <Button size="sm" @click="onSubmit" class="bg-blue-500" :disabled="form.hasErrors">{{ saveLabel }}</Button>
    </div>
</template>
