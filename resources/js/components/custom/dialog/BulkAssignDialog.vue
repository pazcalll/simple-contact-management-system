<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'
import { ref, watch } from 'vue';

const props = defineProps<{open?: boolean}>();
const emit = defineEmits(['update:open', 'submit']);
const internalOpen = ref<boolean>(props.open ?? false)

// Sync internalOpen when parent changes open prop
watch(() => props.open, (val) => {
    internalOpen.value = val ?? false
})

// Emit update:open when internalOpen changes (two-way binding)
watch(internalOpen, (val) => {
    emit('update:open', val)
});

function closeDialog() {
    internalOpen.value = false
    emit('update:open', false)
}
</script>

<template>
  <Dialog v-model:open="internalOpen">
    <DialogTrigger as-child>
        <slot name="trigger"/>
    </DialogTrigger>
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>Assign Data</DialogTitle>
        <DialogDescription>Names below will be assigned to the checked data</DialogDescription>
      </DialogHeader>
      <div class="flex items-center flex-col gap-3 w-full">
        <slot name="content" />
      </div>
      <DialogFooter class="sm:justify-start">
        <DialogClose as-child>
          <Button type="button" @click="closeDialog" variant="secondary">
            Close
          </Button>
          <Button type="submit" @click="$emit('submit')" variant="default">
            Submit
          </Button>
        </DialogClose>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
