<script setup lang="ts">
import { PrecognitionForm } from '@/types';
import { provide } from 'vue';

const { form } = defineProps<{
    form: PrecognitionForm;
}>();

const emit = defineEmits(['submit']);

provide('form', form);
</script>

<template>
    <form v-bind="$attrs" @submit.prevent="emit('submit')">
        <slot />
    </form>
</template>
