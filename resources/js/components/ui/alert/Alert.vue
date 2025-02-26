<script setup lang="ts">
import { HTMLAttributes, ref } from 'vue';
import { cn } from '@/lib/utils'
import { type AlertVariants, alertVariants } from '.'
import { X } from 'lucide-vue-next';

const props = defineProps<{
  class?: HTMLAttributes['class']
  variant?: AlertVariants['variant']
    closable?: boolean
}>()

const visible = ref(true);

const close = () => {
  visible.value = false;
}
</script>

<template>
  <div v-if="visible" class="relative" :class="cn(alertVariants({ variant }), props.class)" role="alert">
      <div v-if="closable" class="absolute top-2 right-3">
            <X class="w-5 h-5 cursor-pointer" @click="close" />
      </div>
    <slot />
  </div>
</template>
