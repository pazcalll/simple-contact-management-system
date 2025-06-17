<script setup lang="ts">
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { ref, watch, computed, onMounted } from 'vue';

const props = defineProps<{
  selectedId: number|string|null;
  placeholder: string|null;
  convertable: { name: string, id: number, group: string, color?: string }[];
}>();

const emit = defineEmits(['update:model-value', 'update:selected-id']);

const selectedItem = ref<{name: string, id: number, group: string, color?: string}|null>(null);

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

// Update selectedItem when selectedId changes
watch(() => props.selectedId, (newId) => {
  if (newId === null) {
    selectedItem.value = null;
    return;
  }
  
  // Find the item with matching id from the convertable array
  const foundItem = props.convertable.find(item => item.id === newId);
  selectedItem.value = foundItem || null;
}, { immediate: true });

// Emit when selectedItem changes
watch(selectedItem, (newValue) => {
  emit('update:model-value', newValue);
  // Also emit update:selected-id to keep them in sync
  emit('update:selected-id', newValue?.id || null);
});

// Initialize selectedItem based on selectedId on mount
onMounted(() => {
  if (props.selectedId !== null) {
    const foundItem = props.convertable.find(item => item.id === props.selectedId);
    selectedItem.value = foundItem || null;
  }
});
</script>

<template>
  <Select v-model="selectedItem">
      <SelectTrigger class="w-full" :style="borderStyle">
          <SelectValue :placeholder="`${placeholder ?? 'Select item'}`" />
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