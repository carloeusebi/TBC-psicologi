<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import SidebarBetaAlert from '@/components/SidebarBetaAlert.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpenText, Github, LayoutGrid, List, ListChecks, ReceiptEuroIcon, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Pazienti',
        href: '',
        icon: Users,
    },
    {
        title: 'Valutazioni',
        href: '',
        icon: ListChecks,
    },
    {
        title: 'Fatture',
        href: '',
        icon: ReceiptEuroIcon,
    },
    {
        title: 'Questionari',
        href: '',
        icon: List,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Documentazione',
        icon: BookOpenText,
        href: '',
    },
    {
        title: 'GitHub',
        icon: Github,
        href: 'https://github.com/carloeusebi/tbc-psicologi.git',
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <SidebarBetaAlert />
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
