<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem, SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const canChangePassword = usePage<SharedData>().props.auth.canChangePassword;

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profilo',
        href: route('profile.edit', {}, false),
    },
    {
        title: 'Password',
        href: route('password.edit', {}, false),
        hidden: !canChangePassword,
    },
    {
        title: 'Tema',
        href: route('appearance', {}, false),
    },
    {
        title: 'Abbonamento',
        href: route('subscription', {}, false),
    },
];

const currentPath = window.location.pathname;
</script>

<template>
    <div class="px-4 py-6">
        <Heading title="Impostazioni" description="Gestisci il profilo e le impostazioni dell'account." />

        <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-x-12 lg:space-y-0">
            <aside class="w-full max-w-xl lg:w-48">
                <nav class="flex flex-col space-x-0 space-y-1">
                    <template v-for="item in sidebarNavItems" :key="item.href">
                        <Button
                            v-if="!item.hidden"
                            variant="ghost"
                            :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                            as-child
                        >
                            <Link :href="item.href" prefetch>
                                {{ item.title }}
                            </Link>
                        </Button>
                    </template>
                </nav>
            </aside>

            <Separator class="my-6 md:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
