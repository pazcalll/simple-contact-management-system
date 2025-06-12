<script setup lang="ts">
import { TLead } from '@/types/custom';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogContent from '../ui/dialog/DialogContent.vue';
import DialogDescription from '../ui/dialog/DialogDescription.vue';
import DialogHeader from '../ui/dialog/DialogHeader.vue';
import DialogTitle from '../ui/dialog/DialogTitle.vue';
import Label from '../ui/label/Label.vue';
import { ref, watch } from 'vue';
import DialogFooter from '../ui/dialog/DialogFooter.vue';
import DialogClose from '../ui/dialog/DialogClose.vue';
import Button from '../ui/button/Button.vue';
import { PlusIcon } from 'lucide-vue-next';

const props = defineProps<{
    isDetailDialogOpen: boolean;
    selectedLead: TLead|null;
}>();
const emit = defineEmits(['update:is-detail-dialog-open']);

const isDetailDialogOpen = ref<boolean>(props.isDetailDialogOpen ?? false);
const selectedLead = ref<TLead|null>(props.selectedLead);

watch(
    () => props.isDetailDialogOpen,
    (val) => isDetailDialogOpen.value = val ?? false
);

watch(
    () => props.selectedLead,
    (val) => selectedLead.value = val ?? null,
)

watch(
    isDetailDialogOpen,
    (val) => emit('update:is-detail-dialog-open', val),
)

</script>

<template>
    <Dialog :open="isDetailDialogOpen" @update:open="emit('update:is-detail-dialog-open', $event)">
        <DialogContent class="sm:max-w-[25rem] lg:max-w-[40rem]">
            <DialogHeader>
                <DialogTitle>Detail</DialogTitle>
                <DialogDescription>
                    This contains the detail and notes of an account.
                </DialogDescription>
            </DialogHeader>
            <div class="grid grid-cols-2 gap-2 py-4">
                <div class="grid grid-cols-1">
                    <Label for="name" class="text-right">
                        Name
                    </Label>
                    <p class="text-gray-400">{{ selectedLead?.name }}</p>
                </div>
                <div class="grid grid-cols-1">
                    <Label for="email" class="text-right">
                        Email
                    </Label>
                    <p class="text-gray-400 break-words">{{ selectedLead?.email }}</p>
                </div>
                <div class="grid grid-cols-1">
                    <Label for="mobile" class="text-right">
                        Mobile
                    </Label>
                    <p class="text-gray-400">{{ selectedLead?.mobile_number }}</p>
                </div>
                <div class="grid grid-cols-1">
                    <Label for="status" class="text-right">
                        Status
                    </Label>
                    <p class="text-gray-400">{{ selectedLead?.lead_status?.name }}</p>
                </div>
            </div>
            <div class="w-full">
                <p>Notes:</p>
                <textarea class="border-2 border-gray-300 rounded-lg w-full"></textarea>
                <DialogFooter class="sm:justify-start">
                    <DialogClose as-child>
                        <Button type="submit" variant="default">
                            <PlusIcon></PlusIcon> Add Note
                        </Button>
                    </DialogClose>
                </DialogFooter>
            </div>
            <div class="w-full max-h-[12rem] space-y-[1rem] overflow-y-auto">
                <div class="w-full">
                    <div class="w-full flex justify-between">
                        <Label class="font-bold">Name</Label>
                        <Label class="text-gray-400">2023-01-01</Label>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa mollitia quisquam delectus dolorem? Sit explicabo officia doloribus ad nam. Nesciunt dicta aperiam ex voluptas aliquid pariatur ea consequuntur consectetur laborum.</p>
                </div>
                <div class="w-full mt-3">
                    <div class="w-full flex justify-between">
                        <Label class="font-bold">Name</Label>
                        <Label class="text-gray-400">2023-01-01</Label>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa mollitia quisquam delectus dolorem? Sit explicabo officia doloribus ad nam. Nesciunt dicta aperiam ex voluptas aliquid pariatur ea consequuntur consectetur laborum.</p>
                </div>
                <div class="w-full mt-3">
                    <div class="w-full flex justify-between">
                        <Label class="font-bold">Name</Label>
                        <Label class="text-gray-400">2023-01-01</Label>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Culpa mollitia quisquam delectus dolorem? Sit explicabo officia doloribus ad nam. Nesciunt dicta aperiam ex voluptas aliquid pariatur ea consequuntur consectetur laborum.</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
