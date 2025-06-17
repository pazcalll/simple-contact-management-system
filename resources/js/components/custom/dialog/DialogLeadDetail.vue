<script setup lang="ts">
import { TLead } from '@/types/custom';
import Dialog from '../../ui/dialog/Dialog.vue';
import DialogContent from '../../ui/dialog/DialogContent.vue';
import DialogDescription from '../../ui/dialog/DialogDescription.vue';
import DialogHeader from '../../ui/dialog/DialogHeader.vue';
import DialogTitle from '../../ui/dialog/DialogTitle.vue';
import Label from '../../ui/label/Label.vue';

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
            <div :class="`w-full max-h-[${height}] space-y-[1rem] overflow-y-auto`" v-if="selectedLead?.lead_notes !== null">
                <div class="w-full rounded-md p-2 hover:bg-gray-200" v-for="(note, index) in selectedLead.lead_notes" v-bind:key="index">
                    <div class="w-full flex justify-between">
                        <Label class="font-bold">{{ note.user.name }}</Label>
                        <Label class="text-gray-400">{{ new Date(note.created_at).toISOString().slice(0, 10) }}</Label>
                    </div>
                    <p>{{ note.note }}</p>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
