<script setup lang="ts">
import { ref, watch } from 'vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const props = defineProps<{
  items: { id: number; name: string }[];
  placeholder: 'Select an item' | string;
  selectedId?: number | null;
}>();

const emit = defineEmits(['update:selected-id']);

const selectedValue = ref(props.selectedId || null);

watch(selectedValue, (newValue) => {
  emit('update:selected-id', newValue);
});
</script>

<template>
  <Select v-model="selectedValue">
    <SelectTrigger class="w-full cursor-pointer">
      <SelectValue :placeholder="placeholder" />
    </SelectTrigger>
    <SelectContent>
      <SelectItem v-for="item in props.items" :key="item?.id" :value="item?.id">
        {{ item.name }}
      </SelectItem>
    </SelectContent>
  </Select>
</template>
