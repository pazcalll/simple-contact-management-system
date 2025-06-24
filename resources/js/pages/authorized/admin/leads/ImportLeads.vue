<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Dialog from '@/components/ui/dialog/Dialog.vue';
import DialogClose from '@/components/ui/dialog/DialogClose.vue';
import DialogContent from '@/components/ui/dialog/DialogContent.vue';
import DialogDescription from '@/components/ui/dialog/DialogDescription.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import DialogHeader from '@/components/ui/dialog/DialogHeader.vue';
import DialogTitle from '@/components/ui/dialog/DialogTitle.vue';
import DialogTrigger from '@/components/ui/dialog/DialogTrigger.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { useForm } from '@inertiajs/vue3';
import { CopyCheckIcon } from 'lucide-vue-next';
import { ref } from 'vue';

const file = ref<File | null>(null);
const isOpen = ref(false);

const form = useForm({
    file: null as File | null,
});

function onFileChange(event: Event) {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        file.value = target.files[0];
    } else {
        file.value = null;
    }
}

function submit() {
    if (file.value) {
        form.file = file.value;
        form.post('/admins/leads/import', {
            onSuccess: () => {
                // Reset the file input after successful submission
                file.value = null;
                // Optionally, you can close the dialog or reset the form
                isOpen.value = false;
            },
            onError: (errors) => {
                console.error('Import failed:', errors);
            },
        });
    } else {
        alert('Please select a CSV file to upload.');
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
                <p class="text-sm text-gray-600">
                    Before importing, please ensure that your CSV file is formatted correctly.
                    You can download the templates below to help you format your data correctly:
                </p>
                <div class="space-x-2 space-y-2 w-full">
                    <Button class="bg-blue-500 hover:bg-blue-600 text-white">
                        <a href="/storage/leads-upload-template.csv" class="w-full" target="_blank">
                            Download Leads Template
                        </a>
                    </Button>
                </div>
                <div class="space-y-2">
                    <Label>Upload CSV file</Label>
                    <Input
                        type="file"
                        accept=".csv"
                        @change="onFileChange"
                        class="mt-2"
                    />
                </div>
            </div>
            <DialogFooter>
                <DialogClose>
                    <Button type="button" variant="secondary">
                        Close
                    </Button>
                </DialogClose>
                <Button variant="default" @click="submit" :disabled="!file">
                    Submit
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
