<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import type { BreadcrumbItem, Invoice, Plan, PlanInterval, Subscription } from '@/types';
import { Head } from '@inertiajs/vue3';
import { format } from 'date-fns';
import { Check } from 'lucide-vue-next';
import { computed } from 'vue';

const { subscription, plan } = defineProps<{
    plan: Plan;
    subscription: Subscription | null;
    invoices: Array<Invoice>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Abbonamento',
        href: route('subscription.index'),
    },
];

const onGracePeriod = computed(() => subscription?.stripe_status === 'active' && subscription?.ends_at);

const recurrence = computed(() => {
    if (!subscription) {
        return null;
    }

    let recurrence: null | PlanInterval = null;

    const priceId = subscription.stripe_price;

    if (plan.pricesId.monthly === priceId) {
        recurrence = 'monthly';
    } else if (plan.pricesId.yearly === priceId) {
        recurrence = 'yearly';
    }

    return recurrence;
});

const gracePeriodEndsAt = computed(() => {
    if (!subscription?.ends_at) {
        return null;
    }

    return format(subscription.ends_at, 'd MMMM Y');
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Abbonamento" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Piano" description="Gestisci qui il tuo abbonamento" />

                <div>
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-medium">Piano</h3>
                            <p class="text-sm text-muted-foreground">Il tuo piano</p>
                        </div>
                    </div>

                    <Card>
                        <CardHeader class="mb-2 flex justify-between">
                            <CardTitle class="justify-between md:flex">
                                <span>{{ plan.name }}</span>
                                <Badge v-if="onGracePeriod" class="bg-yellow-400 text-gray-700 hover:bg-yellow-400">
                                    Il tuo abbonamento scadrà il {{ gracePeriodEndsAt }}
                                </Badge>
                            </CardTitle>
                            <CardDescription>{{ plan.prices[recurrence ?? 'monthly'] }}</CardDescription>
                            <CardDescription>{{ plan.description }}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ul class="space-y-1">
                                <li v-for="feature in plan.features" :key="feature">
                                    <Check class="mr-2 inline h-4 w-4 text-blue-600" stroke-width="3" />
                                    <span class="text-muted-foreground">{{ feature }}</span>
                                </li>
                            </ul>
                        </CardContent>
                    </Card>
                </div>
                <HeadingSmall title="Gestisci Abbonamento" description="Gestisci qui il tuo abbonamento">
                    <a :href="route('subscription.edit')">
                        <Button variant="outline"> Gestisci Abbonamento</Button>
                    </a>
                </HeadingSmall>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
