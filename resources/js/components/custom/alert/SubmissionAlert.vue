<script lang="ts">
// Export types for use in other components
export type TSubmissionAlert = {
  message: string | null;
  isShow: boolean | null;
};
</script>

<script setup lang="ts">
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import { ref, watch } from 'vue';

const props = defineProps<TSubmissionAlert>();
const emit = defineEmits(['update:is-show']);

// Control the visibility of the alert
const isVisible = ref(props.isShow);

// Function to close the alert
const closeAlert = () => {
  isVisible.value = false;
  emit('update:is-show', false);
};

// Watch for changes in isShow prop to update visibility
watch(
  () => props.isShow,
  (newValue) => {
    isVisible.value = newValue;
  },
);
</script>

<template>
  <Alert v-if="isVisible" class="relative bg-green-500">
    <div class="absolute top-2 right-2">
      <button @click="closeAlert" class="mr-2 text-3xl text-white hover:text-gray-200 focus:outline-none" aria-label="Close alert">&times;</button>
    </div>
    <AlertTitle class="text-white">Success</AlertTitle>
    <AlertDescription class="text-white">
      {{ props.message }}
    </AlertDescription>
  </Alert>
</template>
