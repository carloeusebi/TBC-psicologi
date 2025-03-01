<script setup lang="ts">
import type { Row } from '@tanstack/vue-table';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuRadioGroup,
    DropdownMenuRadioItem,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuSub,
    DropdownMenuSubContent,
    DropdownMenuSubTrigger,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import { EllipsisIcon } from 'lucide-vue-next';

defineProps<{
    row: Row<any>;
    actions: Array<{
        label: string;
        icon?: Component;
        onClick?: (row: Row<any>) => void;
    }>;
}>();

</script>

<template>
    <DropdownMenu>
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
            <template v-for="action in actions" :key="action.label">
                <DropdownMenuItem @click="action.onClick(row)">
                    <Component v-if="action.icon" :is="action.icon" class="h-4 w-4 mr-2" />
                    {{ action.label }}
                </DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>
</template>