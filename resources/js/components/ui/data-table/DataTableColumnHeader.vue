<script setup lang="ts">
import type { Column } from '@tanstack/vue-table';
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger
} from '@/components/ui/dropdown-menu';
import {
    ArrowDownWideNarrowIcon,
    ArrowUpNarrowWideIcon,
    ArrowDownUpIcon,
    EyeOffIcon,
    CircleXIcon
} from 'lucide-vue-next';

interface DataTableColumnHeaderProps {
    column: Column<any, any>;
    title: string;
}

defineProps<DataTableColumnHeaderProps>();
</script>

<script lang="ts">
export default {
    inheritAttrs: false
};
</script>

<template>
    <div v-if="column.getCanSort()" :class="cn('flex items-center space-x-2 mx-2', $attrs.class ?? '')">
        <DropdownMenu>
            <DropdownMenuTrigger as-child>
                <Button
                    variant="ghost"
                    size="sm"
                    class="-ml-3 h-8 data-[state=open]:bg-accent"
                >
                    <span>{{ title }}</span>
                    <ArrowDownWideNarrowIcon v-if="column.getIsSorted() === 'desc'" class="ml-2 h-4 w-4" />
                    <ArrowUpNarrowWideIcon v-else-if=" column.getIsSorted() === 'asc'" class="ml-2 h-4 w-4" />
                    <ArrowDownUpIcon v-else class="ml-2 h-4 w-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="start">
                <DropdownMenuItem v-if="column.getIsSorted() !== 'asc'" @click="column.toggleSorting(false)">
                    <ArrowUpNarrowWideIcon class="mr-2 h-3.5 w-3.5 text-muted-foreground/70" />
                    Crescente
                </DropdownMenuItem>
                <DropdownMenuItem v-if="column.getIsSorted() !== 'desc'" @click="column.toggleSorting(true)">
                    <ArrowDownWideNarrowIcon class="mr-2 h-3.5 w-3.5 text-muted-foreground/70" />
                    Decrescente
                </DropdownMenuItem>
                <DropdownMenuItem v-if="column.getIsSorted() !== false" @click="column.clearSorting()">
                    <CircleXIcon class="mr-2 h-3.5 w-3.5 text-muted-foreground/70" />
                    Rimuovi Ordinamento
                </DropdownMenuItem>
                <div v-if="column.getCanHide()">
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="column.toggleVisibility(false)">
                        <EyeOffIcon class="mr-2 h-3.5 w-3.5 text-muted-foreground/70" />
                        Nascondi
                    </DropdownMenuItem>
                </div>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>

    <div v-else :class="$attrs.class">
        {{ title }}
    </div>
</template>
