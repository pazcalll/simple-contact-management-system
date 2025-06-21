<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogTrigger from '@/components/ui/dialog/DialogTrigger.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { CopyCheckIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const file = ref<File | null>(null);

function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        file.value = target.files[0];
    } else {
        file.value = null;
    }
}

</script>

<template>
    <Dialog>
        <DialogTrigger>
            <Button class="w-full" variant="secondary">
                <CopyCheckIcon></CopyCheckIcon>
                Import Leads
            </Button>
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Import Leads</DialogTitle>
                <DialogDescription>
                    Put your excel file here to import leads. The file should be in CSV format.
                </DialogDescription>
            </DialogHeader>
            <div class="space-y-4">
                <div class="space-x-2 space-y-2 w-full">
                    <Button class="bg-blue-500 hover:bg-blue-600 text-white">
                        <a href="/leads/template" class="w-full" target="_blank">
                            Download Leads Template
                        </a>
                    </Button>
                    <Button class="bg-blue-500 hover:bg-blue-600 text-white">
                        <a href="/lead-statuses/csv" class="w-full" target="_blank">
                            Download Statuses Template
                        </a>
                    </Button>
                </div>
                <div class="space-y-2">
                    <Label>CSV file</Label>
                    <Input
                        type="file"
                        accept=".csv"
                        @change="onFileChange"
                        class="mt-2"
                    />
                </div>
            </div>
            <DialogFooter>
                <Button type="button" variant="secondary" @click="$emit('update:open', false)">
                    Close
                </Button>
                <Button type="submit" variant="default" @click="$emit('submit')">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>