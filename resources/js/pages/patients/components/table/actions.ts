import { TableAction } from '@/types';
import { ArchiveIcon, ListChecksIcon } from 'lucide-vue-next';

export const actions: Array<TableAction> = [
    {
        label: 'Crea Valutazione',
        icon: ListChecksIcon,
        onClick: () => void null,
    },
    {
        label: 'Archivia',
        icon: ArchiveIcon,
        onClick: () => void null,
    },
];
