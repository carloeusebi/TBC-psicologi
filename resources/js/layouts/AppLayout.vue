<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { toast } from 'vue-sonner';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const { flash } = usePage<SharedData>().props;

onMounted(() => {
    if (flash.success) {
        toast.success('Successo', {
            description: flash.success,
        });
    }

    if (flash.error) {
        toast.error('Errore', {
            description: flash.error,
            duration: 999999,
        });
    }
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster rich-colors position="top-right" />
    </AppLayout>
</template>
