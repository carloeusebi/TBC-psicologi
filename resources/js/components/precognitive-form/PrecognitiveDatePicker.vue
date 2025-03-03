<script setup lang="ts">
import DatePicker from '@/components/ui/date-picker/DatePicker.vue';
import { Label } from '@/components/ui/label';
import { PrecognitionForm } from '@/types';
import { inject } from 'vue';

defineProps<{
    name: string;
    label?: string;
}>();

const form = inject<PrecognitionForm>('form');

const modelValue = defineModel<string | null | undefined>();
</script>

<template>
    <div>
        <Label v-if="label" :for="name" :class="{ 'text-red-500': form?.invalid(name) }">{{ label }}</Label>
        <DatePicker :id="name" v-model="modelValue" @update:model-value="form?.validate(name)" v-bind="$attrs" />
        <div v-if="form?.invalid(name)" class="text-sm text-red-500">{{ form.errors[name] }}</div>
    </div>
</template>
