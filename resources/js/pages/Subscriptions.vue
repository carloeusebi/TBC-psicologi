<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import SubscriptionCard from '@/components/SubscriptionCard.vue';
import { Button } from '@/components/ui/button';
import { RadioGroup } from '@/components/ui/radio-group';
import type { Plan, PriceInterval } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
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

    <main class="divide-weak -mb-2 grid h-screen grid-cols-1 divide-x overflow-hidden text-sm *:overflow-y-auto md:grid-cols-2">
        <div class="mx-auto flex w-full max-w-xl flex-col items-start px-6">
            <div class="sticky top-0 z-30 -mx-1 w-[calc(100%+0.5rem)]">
                <div class="sticky top-0 z-30 -mx-1 w-[calc(100%+0.5rem)]">
                    <div class="flex w-full bg-gradient-to-b from-background from-[75%] to-transparent px-1 py-10">
                        <Link :href="route('subscription.index')" class="flex items-center gap-2">
                            <AppLogo />
                        </Link>
                    </div>
                </div>
                <div>Piani</div>
                <div class="mt-10 w-full flex-1">
                    <RadioGroup v-model="selected" :default-value="currentPlan.id">
                        <SubscriptionCard v-for="plan in plans" :key="plan.id" :plan :is-current="isCurrent(plan)" :selected-interval />
                    </RadioGroup>
                </div>
            </div>
            <div class="flex w-full bg-gradient-to-t from-background from-[65%] to-transparent px-1 pb-8 pt-10">
                <form method="POST" :action="route('subscription.store')" class="w-full">
                    <input type="hidden" name="_token" :value="csrf_token" />
                    <input type="hidden" name="price" :value="selected.prices.find(({ interval }) => interval === selectedInterval)?.stripe_id" />
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
