<script setup lang="ts">
import DataTablePagination from '@/components/ui/data-table/DataTablePagination.vue';
import DataTableToolbar from '@/components/ui/data-table/DataTableToolbar.vue';
import { Input } from '@/components/ui/input';
import { TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { valueUpdater } from '@/lib/utils';
import { Patient } from '@/types';
import { Link } from '@inertiajs/vue3';
import { rankItem } from '@tanstack/match-sorter-utils';
import type {
    ColumnDef,
    ColumnFiltersState,
    FilterFn,
    GlobalFilterTableState,
    PaginationState,
    SortingState,
    VisibilityState,
} from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, getFilteredRowModel, getPaginationRowModel, getSortedRowModel, useVueTable } from '@tanstack/vue-table';
import { useDebounceFn, useLocalStorage } from '@vueuse/core';

interface DataTableProps {
    columns: ColumnDef<Patient>[];
    data: Array<Patient>;
}

const props = defineProps<DataTableProps>();

const sorting = useLocalStorage<SortingState>('dt.patients.sorting', [{ id: 'therapy_start_date', desc: true }]);
const globalFilter = useLocalStorage<GlobalFilterTableState>('dt.patients.global-filter', '');
const columnFilters = useLocalStorage<ColumnFiltersState>('dt.patients.column-filters', []);
const columnVisibility = useLocalStorage<VisibilityState>('dt.patients.visibility', {});
const pagination = useLocalStorage<PaginationState>('dt.patients.pagination', { pageIndex: 0, pageSize: 10 });

const globalFilterFn: FilterFn<Patient> = (row, _, filterValue) => {
    const terms = filterValue.toLowerCase().split(' ');
    const patient = row.original;

    return terms.every((term) => rankItem(patient.name.toLowerCase(), term).passed);
};

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
        get pagination() {
            return pagination.value;
        },
    },
    globalFilterFn,
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
    onGlobalFilterChange: (updaterOrValue) => valueUpdater(updaterOrValue, globalFilter),
    onPaginationChange: (updaterOrValue) => valueUpdater(updaterOrValue, pagination),
    onColumnFiltersChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnFilters),
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
});

const updateFilter = useDebounceFn((value: string) => {
    table.setGlobalFilter(value);
}, 250);
</script>

<template>
    <div class="space-y-4">
        <DataTableToolbar :table>
            <Input
                placeholder="Filtra Pazienti..."
                :model-value="table.getState().globalFilter"
                class="h-8 w-full lg:w-[250px] xl:w-[500px]"
                @input="updateFilter(String($event.target.value))"
                @keyup.esc="table.resetGlobalFilter()"
            />
        </DataTableToolbar>
        <div class="w-full overflow-scroll rounded-md border">
            <Table class="w-full">
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody class="[&>*:nth-child(odd)]:bg-muted/50">
                    <template v-if="table.getRowModel().rows?.length">
                        <template v-for="row in table.getRowModel().rows" :key="row.id">
                            <Link :href="route('patients.show', row.original.id)" prefetch class="table-row hover:!bg-muted">
                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </Link>
                        </template>
                    </template>

                    <TableRow v-else>
                        <TableCell :colspan="columns.length" class="h-24 text-center"> Nussun Paziente trovato. </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <DataTablePagination :table />
    </div>
</template>
