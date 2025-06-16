<script setup lang="ts">
import { TLead } from '@/types/custom';
import Dialog from '../ui/dialog/Dialog.vue';
import DialogContent from '../ui/dialog/DialogContent.vue';
import DialogDescription from '../ui/dialog/DialogDescription.vue';
import DialogHeader from '../ui/dialog/DialogHeader.vue';
import DialogTitle from '../ui/dialog/DialogTitle.vue';
import Label from '../ui/label/Label.vue';

defineProps<{
    open: boolean;
    selectedLead: TLead|null;
}>();
const emit = defineEmits(['update:open', 'update:selected-lead']);

const height = window.innerHeight >= 800 ? '24rem' : '12rem';

function onDialogUpdateOpen(val: boolean) {
    emit('update:open', val);
    if (!val) emit('update:selected-lead', null);
}
</script>

<template>
    <Dialog
        :open="open"
        @update:open="onDialogUpdateOpen"
    >
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
                <slot name="content"></slot>
            </div>
            <div :class="`w-full max-h-[${height}] space-y-[1rem] overflow-y-auto`">
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
