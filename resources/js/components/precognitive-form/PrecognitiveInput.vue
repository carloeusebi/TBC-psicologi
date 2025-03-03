<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { cn } from '@/lib/utils';
import { PrecognitionForm } from '@/types';
import { type Component, computed, type HTMLAttributes, inject } from 'vue';

const props = withDefaults(
    defineProps<{
        name: string;
        label?: string;
        placeholder?: string;
        icon?: Component;
        iconPosition?: 'before' | 'after';
        containerClass?: HTMLAttributes['class'];
        inputClass?: HTMLAttributes['class'];
    }>(),
    {
        iconPosition: 'before',
    },
);

const form = inject<PrecognitionForm>('form');

type ModelValue = string | number | null | undefined;

const [modelValue, modelModifiers] = defineModel<ModelValue, 'trim' | 'uppercase' | 'ucfirst'>({
    set(value) {
        if (modelModifiers.uppercase) {
            return value?.toString().toUpperCase();
        }

        if (modelModifiers.ucfirst) {
            return value?.toString().replace(/\b\w/g, (char) => char.toUpperCase());
        }

        return value;
    },
});

const inputClasses = computed(() => {
    const iconClass = props.icon && props.iconPosition === 'before' ? 'pl-10' : 'pr-10';
    return cn(props.inputClass, props.icon ? iconClass : '');
});
</script>

<template>
    <div :class="containerClass">
        <Label v-if="label" :for="name" :class="{ 'text-red-500': form?.invalid(name) }">{{ label }}</Label>
        <div class="relative">
            <Input :id="name" :placeholder v-model.trim="modelValue" @input="form?.validate(name)" :class="inputClasses" />
            <span
                v-if="icon"
                :class="iconPosition === 'before' ? 'start-0' : 'end-0'"
                class="absolute inset-y-0 flex items-center justify-center px-2"
            >
                <component :is="icon" class="size-5 text-muted-foreground" />
            </span>
        </div>
        <div v-if="form?.invalid(name)" class="text-sm text-red-500">{{ form?.errors[name] }}</div>
    </div>
</template>
