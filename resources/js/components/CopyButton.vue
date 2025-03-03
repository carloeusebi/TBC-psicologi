<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';
import { UseClipboard } from '@vueuse/components';
import { Copy } from 'lucide-vue-next';

withDefaults(defineProps<{ source: string; tooltip?: string }>(), { tooltip: 'Copia negli appunti' });
</script>

<template>
    <UseClipboard v-slot="{ copy, copied }" :source>
        <TooltipProvider>
            <Tooltip>
                <TooltipTrigger as-child>
                    <Button type="button" variant="link" size="icon" @click="copy()">
                        <Copy class="text-muted-foreground" />
                    </Button>
                    <Transition>
                        <span v-if="copied"> Copiato! </span>
                    </Transition>
                </TooltipTrigger>
                <TooltipContent>
                    <p v-html="tooltip" />
                </TooltipContent>
            </Tooltip>
        </TooltipProvider>
    </UseClipboard>
</template>
