import { ButtonVariants } from '@/components/ui/button';
import { Component, HTMLAttributes, reactive, ref } from 'vue';

interface Params {
    title: string;
    message: string;
    icon?: Component;
    cancel: {
        label: string;
    };
    onAccept: (args: any) => void;
    accept: {
        label: string;
        icon?: Component;
        class?: HTMLAttributes['class'];
        variant?: ButtonVariants['variant'];
    };
    processing: boolean;
}

const open = ref(false);

const dialog = reactive<Params>({
    title: 'Conferma',
    message: 'Sei sicuro di voler procedere?',
    cancel: {
        label: 'Annulla',
    },
    accept: {
        label: 'Conferma',
    },
    onAccept: () => void null,
    processing: false,
});

export const useConfirm = () => {
    const require = (params: Partial<Params>) => {
        Object.assign(dialog, params);

        dialog.processing = false;
        open.value = true;
    };

    return {
        open,
        dialog,
        require,
    };
};
