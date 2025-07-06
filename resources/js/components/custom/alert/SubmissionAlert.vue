<script lang="ts">
// Export types for use in other components
export type TSubmissionAlert = {
  message: string | null;
  isSuccess: boolean | null;
};
</script>

<script setup lang="ts">
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import { ref, watch } from 'vue';

const props = defineProps<TSubmissionAlert>();

// Control the visibility of the alert
const isVisible = ref(props.isSuccess);

// Function to close the alert
const closeAlert = () => {
  isVisible.value = false;
};

// Watch for changes in isSuccess prop to update visibility
watch(() => props.isSuccess, (newValue) => {
  isVisible.value = newValue;
});
</script>

<template>
  <Alert v-if="isVisible" class="bg-green-500 relative">
      <div class="absolute top-2 right-2">
        <button
          @click="closeAlert"
          class="text-white hover:text-gray-200 focus:outline-none text-3xl mr-2"
          aria-label="Close alert"
        >
          &times;
        </button>
      </div>
      <AlertTitle class="text-white">Success</AlertTitle>
      <AlertDescription class="text-white">
          {{ props.message }}
      </AlertDescription>
  </Alert>
</template>
