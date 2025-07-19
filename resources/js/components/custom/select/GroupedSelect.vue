<script setup lang="ts">
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { AcceptableValue } from 'reka-ui';
import { computed, ref, watch } from 'vue';

const props = defineProps<{
  selectedId: number | string | null;
  placeholder: string | null;
  convertable: { name: string; id: number; group: string; color?: string }[];
}>();

const emit = defineEmits(['update:selected-id']);

// Create a reactive reference for the selected value
const selectedValue = ref(props.selectedId);

// Watch for changes in props.selectedId and update the local selectedValue
watch(
  () => props.selectedId,
  (newValue) => {
    selectedValue.value = newValue;
  },
);

// Find the currently selected item based on selectedValue
const selectedItem = computed(() => {
  if (selectedValue.value === null) return null;
  return props.convertable.find((item) => item.id === selectedValue.value) || null;
});

// Compute border style based on the selected item
const borderStyle = computed(() => {
  return selectedItem.value?.color ? { borderColor: selectedItem.value.color, borderWidth: '2px' } : {};
});

const groupableArray = <T extends { name: string; id: number; group: string; color?: string }>(convertable: T[]): { [group: string]: T[] } => {
  const groups: { [group: string]: T[] } = {};
  convertable.forEach((status) => {
    const group = status.group || 'Ungrouped';
    if (!groups[group]) {
      groups[group] = [];
    }
    groups[group].push(status);
  });
  return groups;
};

// Handle item selection
const handleSelectionChange = (id: AcceptableValue) => {
  selectedValue.value = (id || null) as number;
  emit('update:selected-id', id || null);
};
</script>

<template>
  <Select v-model="selectedValue" @update:model-value="handleSelectionChange">
    <SelectTrigger class="w-full" :style="borderStyle">
      <SelectValue :placeholder="`${selectedItem?.name ?? placeholder ?? 'Select item'}`" />
    </SelectTrigger>
    <SelectContent>
      <SelectGroup v-for="[_groupableIndex, groupable] in Object.entries(groupableArray(convertable))" :key="_groupableIndex">
        <SelectLabel class="bg-gray-300">{{ _groupableIndex }}</SelectLabel>
        <SelectItem v-for="(item, _itemIndex) in groupable" :key="_itemIndex" :value="item.id">
          <div class="flex items-center">
            <span v-if="item.color" class="mr-2 inline-block h-3 w-3 rounded-full" :style="{ backgroundColor: item?.color ?? 'transparent' }"></span>
            {{ item.name }}
          </div>
        </SelectItem>
      </SelectGroup>
    </SelectContent>
  </Select>
</template>
