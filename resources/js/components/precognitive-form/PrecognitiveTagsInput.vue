<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { PrecognitionForm } from '@/types';
import { type Component, inject, ref, watch } from 'vue';

withDefaults(
    defineProps<{
        name: string;
        label?: string;
        placeholder?: string;
        icon?: Component;
    }>(),
    {
        placeholder: 'Separati dalla virgola...',
    },
);

const form = inject<PrecognitionForm>('form');

const modelValue = defineModel<string | null | undefined>();

const fromModelValue = () => (modelValue.value ? modelValue.value.split(',') : []);

const list = ref<Array<string>>(fromModelValue());

watch(modelValue, () => {
    list.value = fromModelValue();
});
</script>

<template>
    <div v-bind="$attrs">
        <Label v-if="label" :for="name" :class="{ 'text-red-500': form?.invalid(name) }">{{ label }}</Label>
        <div class="relative">
            <TagsInput :class="{ 'pl-10': icon }" :id="name" v-model="list" @update:model-value="modelValue = list.join(',')">
                <TagsInputItem v-for="item in list" :key="item" :value="item">
                    <TagsInputItemText />
                    <TagsInputItemDelete />
                </TagsInputItem>

                <TagsInputInput :placeholder="placeholder" />
                <span v-if="icon" class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                    <component :is="icon" class="size-5 text-muted-foreground" />
                </span>
            </TagsInput>
        </div>
        <div v-if="form?.invalid(name)" class="text-sm text-red-500">{{ form.errors[name] }}</div>
    </div>
</template>
