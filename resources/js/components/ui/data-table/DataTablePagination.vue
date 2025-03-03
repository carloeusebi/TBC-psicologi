<script setup lang="ts">
import type { Table } from '@tanstack/vue-table';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';
import { ChevronLeftIcon, ChevronRightIcon, ChevronsRightIcon, ChevronsLeftIcon } from 'lucide-vue-next';

interface DataTablePaginationProps {
    table: Table<any>;
}

defineProps<DataTablePaginationProps>();
</script>

<template>
    <div class="flex flex-wrap items-center justify-between px-2 gap-2">
        <div class="flex-1 text-sm text-muted-foreground">
            {{ table.getFilteredRowModel().rows.length }}
            {{ table.getFilteredRowModel().rows.length === 1 ? 'Risultato' : 'Risultati' }}
        </div>
        <div class="flex items-center space-x-2">
            <p class="text-sm font-medium">
                Per Pagina
            </p>
            <Select
                :model-value="`${table.getState().pagination.pageSize}`"
                @update:model-value="table.setPageSize"
            >
                <SelectTrigger class="h-8 w-[70px]">
                    <SelectValue :placeholder="`${table.getState().pagination.pageSize}`" />
                </SelectTrigger>
                <SelectContent side="top">
                    <SelectItem
                        v-for="pageSize in [7, 10, 15, 20, 30, 40, 50]" :key="pageSize"
                        :value="`${pageSize}`"
                    >
                        {{ pageSize }}
                    </SelectItem>
                </SelectContent>
            </Select>
        </div>
        <div class="flex grow md:grow-0 justify-between">
            <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                Pagina {{ table.getState().pagination.pageIndex + 1 }} di
                {{ table.getPageCount() }}
            </div>
            <div class="flex items-center space-x-2">
                <Button
                    variant="outline"
                    class="h-8 w-8 p-0 flex"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.setPageIndex(0)"
                >
                    <ChevronsLeftIcon class="h-4 w-4" />
                    <span class="sr-only">Vai alla prima pagina</span>
                </Button>
                <Button
                    variant="outline"
                    class="h-8 w-8 p-0"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.previousPage()"
                >
                    <span class="sr-only">Vai alla pagina precedente</span>
                    <ChevronLeftIcon class="h-4 w-4" />
                </Button>
                <Button
                    variant="outline"
                    class="h-8 w-8 p-0"
                    :disabled="!table.getCanNextPage()"
                    @click="table.nextPage()"
                >
                    <span class="sr-only">Vai alla prossima pagina</span>
                    <ChevronRightIcon class="h-4 w-4" />
                </Button>
                <Button
                    variant="outline"
                    class="h-8 w-8 p-0 flex"
                    :disabled="!table.getCanNextPage()"
                    @click="table.setPageIndex(table.getPageCount() - 1)"
                >
                    <span class="sr-only">Vai all'ultima pagina</span>
                    <ChevronsRightIcon class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
</template>
