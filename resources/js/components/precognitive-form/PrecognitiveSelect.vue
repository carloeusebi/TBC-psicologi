<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { PrecognitionForm } from '@/types';
import { inject, withDefaults } from 'vue';

withDefaults(
    defineProps<{
        name: string;
        label?: string;
        placeholder?: string;
        options: Array<any>;
        optionValue?: string;
        optionLabel?: string;
    }>(),
    {
        optionValue: 'key',
        optionLabel: 'label',
    },
);

const form = inject<PrecognitionForm>('form');

const modelValue = defineModel<string | null | undefined>();
</script>

<template>
    <div>
        <Label v-if="label" :for="name" :class="{ 'text-red-500': form?.invalid(name) }">{{ label }}</Label>
        <Select :id="name" v-model="modelValue" @update:model-value="form?.validate(name)">
            <SelectTrigger>
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem v-for="option in options" :key="option[optionValue]" :value="option[optionValue]">
                    {{ option[optionLabel] }}
                </SelectItem>
            </SelectContent>
        </Select>
        <div v-if="form?.invalid(name)" class="text-sm text-red-500">{{ form.errors[name] }}</div>
    </div>
</template>
