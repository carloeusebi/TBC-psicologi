<script setup lang="ts">
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { SidebarGroup, useSidebar } from '@/components/ui/sidebar';
import { Info } from 'lucide-vue-next';

const sidebar = useSidebar();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <Alert v-if="sidebar.open.value">
            <Info />
            <AlertTitle>Beta</AlertTitle>
            <AlertDescription> Quest'app è in fase di sviluppo. Per favore, segnala eventuali bug o feedback. </AlertDescription>
        </Alert>
    </SidebarGroup>
</template>
