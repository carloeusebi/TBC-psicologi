<script setup lang="ts">
import type { Plan } from '@/types';

defineProps<{
    plans: Array<Plan>;
    secret: string;
}>();
</script>

<template>
    <stripe-pricing-table
        pricing-table-id="prctbl_1QwQE8IZRxSczZbX9a4n4nQ4"
        publishable-key="pk_test_51QWD3iIZRxSczZbXoVeQSN4atYNULkuGRG9EL84AA8STraW28DM5S2NpGsDsOSLFGQAHxtnPvWxpd8sbvQ8KWwXf00pRa6LQOp"
        :customer-session-client-secret="secret"
    >
    </stripe-pricing-table>
</template>
