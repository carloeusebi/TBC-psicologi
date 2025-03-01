<script setup lang="ts">
import type { Table } from '@tanstack/vue-table';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import { XIcon } from 'lucide-vue-next';

import DataTableViewOptions from './DataTableViewOptions.vue';

interface DataTableToolbarProps {
    table: Table<any>;
}

const props = defineProps<DataTableToolbarProps>();

const isFiltered = computed(() => props.table.getState().columnFilters.length > 0 || props.table.getState().globalFilter);

const onResetClick = () => {
    props.table.resetColumnFilters();
    props.table.resetGlobalFilter();
};
</script>

<template>
    <div class="md:flex items-center justify-between space-y-2 md:space-y-0">
        <div class="md:order-2">
            <DataTableViewOptions :table />
        </div>
        <div class="md:flex flex-1 items-center gap-x-2 space-y-2 md:space-y-0">
            <slot />
            <Button
                v-if="isFiltered"
                variant="ghost"
                class="h-8 px-2 lg:px-3"
                @click="onResetClick"
            >
                Reset
                <XIcon class="h-4 w-4" />
            </Button>
        </div>
    </div>
</template>
