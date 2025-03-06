<script setup lang="ts">
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="h-full max-w-[100vw] flex-1 flex-col space-y-8 p-2 md:flex md:p-4 lg:p-8">
            <slot />
        </div>
        <ConfirmDialog />
        <Toaster rich-colors position="top-right" />
    </AppLayout>
</template>
