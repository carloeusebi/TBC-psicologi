<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Label } from '@/components/ui/label';
import { RadioGroupItem } from '@/components/ui/radio-group';
import { Plan } from '@/types';
import { Check } from 'lucide-vue-next';

defineProps<{
    plan: Plan;
    isCurrent: boolean;
    selectedInterval: string;
}>();
</script>

<template>
    <div class="plan-card group relative flex items-center space-x-2 rounded-md border has-[[data-state=checked]]:bg-sidebar">
        <Label class="shadow-xs-with-border relative z-10 flex w-full cursor-pointer items-start gap-5 rounded-md px-6 pb-6 pt-4" :for="plan.id">
            <div class="flex h-6 shrink-0 items-center">
                <RadioGroupItem :id="plan.id" :value="plan" />
            </div>
            <div class="flex-1">
                <div class="text-strong flex w-full items-start justify-between text-base font-medium md:items-center">
                    <div class="items-center gap-4 whitespace-nowrap md:flex">
                        <div>{{ plan.name }}</div>
                        <Badge v-if="isCurrent"> Piano attuale</Badge>
                    </div>
                    <span class="text-right">{{ plan.prices.find(({ interval }) => interval === selectedInterval)?.label }}</span>
                </div>
                <div class="mt-1 flex justify-between gap-4">
                    <p class="font-normal">{{ plan.description }}</p>
                </div>
                <ul class="mt-3 space-y-1">
                    <li v-for="feature in plan.features" :key="feature" class="flex items-center gap-2.5 leading-[20px]">
                        <Check class="size-4 text-blue-600" stroke-width="3" />
                        <span class="text-muted-foreground">{{ feature }}</span>
                    </li>
                </ul>
            </div>
        </Label>
    </div>
</template>
