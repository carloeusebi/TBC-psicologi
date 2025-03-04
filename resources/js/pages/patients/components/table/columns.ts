import DataTableColumnHeader from '@/components/ui/data-table/DataTableColumnHeader.vue';
import DataTableRowActions from '@/components/ui/data-table/DataTableRowActions.vue';
import { Patient } from '@/types';
import type { ColumnDef } from '@tanstack/vue-table';
import { format } from 'date-fns';
import { h } from 'vue';
import { actions } from '../../actions';

export const columns: ColumnDef<Patient>[] = [
    {
        accessorKey: 'first_name',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Nome' }),
        cell: ({ row }) => h('span', { class: 'truncate font-medium' }, row.original.first_name),
        enableHiding: false,
    },
    {
        accessorKey: 'last_name',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Cognome' }),
        cell: ({ row }) => h('span', { class: 'truncate font-medium' }, row.original.last_name),
        enableHiding: false,
    },
    {
        accessorKey: 'email',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Email' }),
        cell: ({ row }) => h('span', { class: 'truncate font-medium' }, row.original.email ?? ''),
        enableSorting: false,
    },
    {
        accessorKey: 'age',
        id: 'età',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Età' }),
        cell: ({ row }) => h('span', { class: 'truncate font-medium' }, row.original.age ?? ''),
    },
    {
        accessorKey: 'therapy_start_date',
        id: 'data inizio terapia',
        header: ({ column }) => h(DataTableColumnHeader, { column, title: 'Data di inizio Terapia' }),
        cell: ({ row }) => h('span', { class: 'truncate font-medium' }, format(row.original.therapy_start_date, ' d MMMM y')),
    },
    {
        id: 'actions',
        cell: ({ row }) => h(DataTableRowActions, { row, actions }),
        enableGlobalFilter: false,
    },
];
