<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Button } from '@/components/ui/button';

import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import {
    DateFormatter,
    type DateValue,
    CalendarDate,
    getLocalTimeZone
} from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const df = new DateFormatter('it-IT', {
    dateStyle: 'long'
});

const model = defineModel<string | null>();
const date = typeof model.value === 'string' ? new Date(model.value as string) : null;

const value = ref<DateValue>();

if (date) {
    value.value = new CalendarDate(date.getFullYear(), date.getMonth() + 1, date.getDate());
}

const onDateChange = (date: DateValue) => {
    model.value = date.toDate('UTC').toISOString();
};

watch(() => model.value,
    (newValue) => {
        if (!newValue) {
            value.value = undefined;
            return;
        }
        const date = new Date(newValue);
        value.value = new CalendarDate(date.getFullYear(), date.getMonth() + 1, date.getDate());
    },
    { immediate: true }
);
</script>

<template>
    <Popover>
        <PopoverTrigger as-child>
            <Button
                variant="outline"
                :class="cn(
              'w-full h-10 justify-start text-left font-normal',
              !value && 'text-muted-foreground',
            )"
            >
                <CalendarIcon class="mr-2 h-4 w-4" />
                {{ value ? df.format(value.toDate(getLocalTimeZone())) : 'Seleziona una data' }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0">
            <Calendar v-model="value" initial-focus @update:model-value="onDateChange" v-bind="$attrs" />
        </PopoverContent>
    </Popover>
</template>