<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import type { Plan, PriceInterval } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import { toast, Toaster } from 'vue-sonner';

const { currentPlan, csrf_token } = defineProps<{
    plans: Array<Plan>;
    currentPlan: Plan;
    csrf_token: string;
}>();

const { errors } = usePage().props;

const selected = ref<Plan>(currentPlan);
const selectedInterval = ref<PriceInterval>('month');

const isCurrent = (plan: Plan) => plan.id === currentPlan.id;

const currentIsSelected = computed(() => selected.value.id === currentPlan.id);

const selectedPrice = computed(() => selected.value.prices.find(({ interval }) => interval === selectedInterval.value)?.label);

onMounted(() => {
    for (const key in errors) {
        toast.error('Errore', {
            description: errors[key],
        });
    }
});
</script>

<template>
    <Head title="Piani" />

    <Toaster rich-colors position="top-right" />

    <main class="divide-weak bg-default -mb-2 grid h-screen grid-cols-1 divide-x overflow-hidden text-sm *:overflow-y-auto md:grid-cols-2">
        <div class="mx-auto flex w-full max-w-xl flex-col items-start px-6">
            <div class="sticky top-0 z-30 -mx-1 w-[calc(100%+0.5rem)]">
                <div class="sticky top-0 z-30 -mx-1 w-[calc(100%+0.5rem)]">
                    <div class="flex w-full bg-gradient-to-b from-white from-[75%] to-transparent px-1 py-10">
                        <Link :href="route('subscription.index')" class="flex items-center gap-2">
                            <AppLogo />
                        </Link>
                    </div>
                </div>
                <div>Piani</div>
                <div class="mt-10 w-full flex-1">
                    <RadioGroup v-model="selected" :default-value="currentPlan.id">
                        <div
                            v-for="plan in plans"
                            :key="plan.id"
                            class="plan-card group relative flex items-center space-x-2 rounded-md border has-[[data-state=checked]]:bg-sidebar"
                        >
                            <Label
                                class="shadow-xs-with-border relative z-10 flex w-full cursor-pointer items-start gap-5 rounded-md px-6 pb-6 pt-4"
                                :for="plan.id"
                            >
                                <div class="flex h-6 shrink-0 items-center">
                                    <RadioGroupItem :id="plan.id" :value="plan" />
                                </div>
                                <div class="flex-1">
                                    <div class="text-strong flex h-6 w-full items-center justify-between text-base font-medium">
                                        <div class="flex items-center gap-4 whitespace-nowrap">
                                            {{ plan.name }}
                                            <Badge v-if="isCurrent(plan)"> Piano attuale</Badge>
                                        </div>
                                        <span class="text-right">{{ plan.prices.find(({ interval }) => interval === selectedInterval)?.label }}</span>
                                    </div>
                                    <div class="mt-1 flex justify-between gap-4">
                                        <p class="font-normal">{{ plan.description }}</p>
                                    </div>
                                    <ul class="mt-3 space-y-1">
                                        <li v-for="feature in plan.features" :key="feature" class="flex items-center gap-2.5 leading-[20px]">
                                            <Check class="h-4 w-4 text-blue-600" stroke-width="3" />
                                            <span class="text-muted-foreground">{{ feature }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </Label>
                        </div>
                    </RadioGroup>
                </div>
            </div>
            <div class="flex w-full bg-gradient-to-t from-white from-[65%] to-transparent px-1 pb-8 pt-10">
                <form method="POST" :action="route('subscription.store')" class="w-full">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="price" :value="selected.prices.find(({ interval }) => interval === selectedInterval).stripe_id" />
                    <Button :disabled="currentIsSelected" class="mt-10 inline w-full"> Passa a {{ selected.name }} </Button>
                </form>
            </div>
        </div>

        <div class="hidden bg-sidebar pt-[6.5rem] md:block">
            <div class="w-full max-w-4xl">
                <div class="mx-auto w-full max-w-xl px-6">
                    <h2 class="text-strong font-medium">Sommario</h2>
                    <div class="mt-10 space-y-8">
                        <div class="space-y-2">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-strong font-medium">Piano</h3>
                            </div>
                            <div>
                                <div class="flex items-start justify-between gap-4">
                                    <div>{{ selected.name }}</div>
                                    <div class="text-strong text-right">
                                        {{ selectedPrice }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<style>
.plan-card:has([data-state='checked']) {
    box-shadow:
        0 0 #0000,
        0 0 #0000,
        0px 0px 0px 3px #0180ff1f,
        0px 0px 0px 1px #0180ff,
        0px 1px 3px 0px #0000001a,
        0px 1px 2px 0px #0000000f;
}
</style>
