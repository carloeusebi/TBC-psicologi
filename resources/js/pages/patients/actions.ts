import { useConfirm } from '@/composables/useConfirm';
import { Patient, SharedData, TableAction } from '@/types';
import { Page } from '@inertiajs/core';
import { router } from '@inertiajs/vue3';
import { ArchiveIcon, ListChecksIcon, Trash, TriangleAlert } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const confirm = useConfirm();

export const actions: TableAction<Patient>[][] = [
    [
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
    ],
    [
        {
            label: 'Elimina',
            icon: Trash,
            class: 'text-red-500 hover:!text-red-600',
            onClick(target) {
                confirm.require({
                    title: 'Attenzione',
                    icon: TriangleAlert,
                    message: `
                        Sei sicuro di voler eliminare <span class="font-bold">${target.name}?</span><br />
                        <span class="font-bold">Questa azione è irreversibile.</span>
                    `,
                    accept: {
                        label: 'Elimina',
                        icon: Trash,
                        variant: 'destructive',
                    },
                    onAccept: () =>
                        router.delete(route('patients.destroy', { patient: target.id }), {
                            onStart: () => (confirm.dialog.processing = true),
                            onSuccess: (params) =>
                                toast.success((params as Page<SharedData>).props.flash.success ?? 'Paziente eliminato con successo!'),
                            onFinish: () => (confirm.open.value = false),
                        }),
                });
            },
        },
    ],
];
