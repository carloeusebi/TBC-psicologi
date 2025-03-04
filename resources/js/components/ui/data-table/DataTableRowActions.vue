<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { EllipsisIcon } from 'lucide-vue-next';
import { TableAction } from '@/types';

defineProps<{
    row: Row<any>;
    actions: Array<Array<TableAction<any>>>;
}>();

</script>

<template>
    <DropdownMenu :modal="false">
        <DropdownMenuTrigger as-child>
            <Button
                variant="ghost"
                class="flex h-8 w-8 p-0 data-[state=open]:bg-muted"
            >
                <EllipsisIcon class="h-4 w-4" />
                <span class="sr-only">Open menu</span>
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <template v-for="(actionGroup, index) in actions" :key="index">
                <template v-for="action in actionGroup" :key="action.label">
                    <DropdownMenuItem @click="action.onClick(row.original)" :class="action.class">
                        <Component v-if="action.icon" :is="action.icon" class="size-4 mr-2" />
                        {{ action.label }}
                    </DropdownMenuItem>
                </template>
                <DropdownMenuSeparator v-if="index < (actions.length - 1)" />
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>