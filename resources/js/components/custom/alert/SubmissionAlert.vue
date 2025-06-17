<script setup lang="ts">
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';

defineProps<{
  message: string,
  isSuccess: boolean,
}>();

</script>

<template>
  <Alert v-if="isSuccess" class="bg-green-500">
      <AlertTitle class="text-white">Success</AlertTitle>
      <AlertDescription class="text-white">
          {{ message }}
      </AlertDescription>
  </Alert>
</template>