<script setup lang="ts">
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { computed, ref } from 'vue';

const props = defineProps<{
  selectedId: number|string|null;
  placeholder: string|null;
  convertable: { name: string, id: number, group: string, color?: string }[];
}>();

const emit = defineEmits(['update:selected-id']);

// Find the currently selected item based on selectedId
const selectedItem = computed(() => {
  if (props.selectedId === null) return null;
  return props.convertable.find(item => item.id === props.selectedId) || null;
});

// Compute border style based on the selected item
const borderStyle = computed(() => {
  return selectedItem.value?.color ? { borderColor: selectedItem.value.color, borderWidth: '2px' } : {};
});

const groupableArray = <T extends { name: string, id: number, group: string, color?: string }> (convertable: T[]): { [group: string]: T[] } => {
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
const handleSelectionChange = (item: any) => {
  emit('update:selected-id', item?.id || null);
};
</script>

<template>
  <Select @update:model-value="handleSelectionChange">
    <SelectTrigger class="w-full" :style="borderStyle">
        <SelectValue :placeholder="`${selectedItem?.name ?? placeholder ?? 'Select item'}`" />
    </SelectTrigger>
    <SelectContent>
        <SelectGroup v-for="[_groupableIndex, groupable] in Object.entries(groupableArray(convertable))" :key="_groupableIndex">
            <SelectLabel class="bg-gray-300">{{ _groupableIndex }}</SelectLabel>
            <SelectItem v-for="(item, _itemIndex) in groupable" :key="_itemIndex" :value="item">
                <div class="flex items-center">
                  <span v-if="item.color" class="inline-block w-3 h-3 mr-2 rounded-full" :style="{ backgroundColor: item.color }"></span>
                  {{ item.name }}
                </div>
            </SelectItem>
        </SelectGroup>
    </SelectContent>
  </Select>
</template>