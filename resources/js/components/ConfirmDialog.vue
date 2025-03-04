<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { useConfirm } from '@/composables/useConfirm';
import { LoaderCircle } from 'lucide-vue-next';

const { open, dialog } = useConfirm();
</script>

<template>
    <AlertDialog v-model:open="open">
        <AlertDialogContent :disable-outside-pointer-events="false">
            <AlertDialogHeader>
                <AlertDialogTitle class="flex items-center gap-2">
                    <Component v-if="dialog.icon" :is="dialog.icon" class="size-7" />
                    {{ dialog.title }}
                </AlertDialogTitle>
                <AlertDialogDescription>
                    <p v-html="dialog.message" />
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>
                    {{ dialog.cancel.label }}
                </AlertDialogCancel>
                <Button :variant="dialog.accept.variant" :disabled="dialog.processing" @click.prevent="dialog.onAccept">
                    <LoaderCircle v-if="dialog.processing" class="animate-spin" />
                    <Component v-else-if="dialog.accept.icon" :is="dialog.accept.icon" class="size-4" />
                    {{ dialog.accept.label }}
                </Button>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
