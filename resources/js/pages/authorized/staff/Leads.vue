<script lang="ts" setup>
import SubmissionAlert from '@/components/custom/alert/SubmissionAlert.vue';
import DialogLeadDetail from '@/components/custom/dialog/DialogLeadDetail.vue';
import GroupedSelect from '@/components/custom/select/GroupedSelect.vue';
import TablePagination, { handleNext, handlePrev } from '@/components/custom/table/TablePagination.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import { getLeadNotesJson } from '@/data/staffs/lead-notes';
import AppLayout from '@/layouts/AppLayout.vue';
import { TFlash, TLead, TLeadNote, TLeadStatus, TPagination } from '@/types/custom';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Loader, PlusCircle, PlusIcon } from 'lucide-vue-next';
import { AcceptableValue } from 'reka-ui';
import { ref } from 'vue';

const page = usePage<TFlash>();
const pagination = ref<TPagination<TLead[]>>(page.props.leads as TPagination<TLead[]>);
const isDetailDialogOpen = ref<boolean>(false);
const isAddingNote = ref<boolean>(false);
const submissionAlertState = ref({
    isSuccess: page.props.flash.success != null,
    message: page.props.flash.success || page.props.flash.error || ''
})

const selectedLead = ref<TLead|null>(null);

const leadStatuses = page.props.leadStatuses as TLeadStatus[];
const formAddNote = useForm({
    note: null,
});

const handleAddNote = async () => {
    isAddingNote.value = true;
    formAddNote.submit(
        'post',
        `/staffs/leads/${selectedLead.value?.id}/notes`,
        {
            onBefore: (data) => {
                console.log(data)
            },
            onSuccess: () => {
                isDetailDialogOpen.value = false;
            },
            onFinish: () => {
                isAddingNote.value = false;
            }
        }
    );
}

const handleGetNotes = async () => {
    const response = await getLeadNotesJson(selectedLead.value?.id ?? 0);
    if (response && typeof response === 'object' && 'message' in response) {
        console.log(response);
        return;
    }
    return response as TLeadNote[];
}

const handleSelectedStatusChange = (leadId: number, event: AcceptableValue) => {
    const toUpdateLead = pagination.value.data.find(lead => lead.id === leadId);

    const form = useForm({
        lead_id: toUpdateLead?.id,
        lead_status_id: event as number,
    });
    form.patch(`/staffs/leads/${toUpdateLead?.id}/status`, {
        onError: (err) => {
            console.log(err)
        },
    })
}

const handleOpenDialog = async (lead: TLead) => {
    selectedLead.value = lead;

    isDetailDialogOpen.value = true;
    if (selectedLead.value) selectedLead.value.lead_notes = await handleGetNotes();
}

const next = () => handleNext({ pagination: pagination, endpoint: '/admins/leads' });
const prev = () => handlePrev({ pagination: pagination, endpoint: '/admins/leads' });
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <SubmissionAlert
                :isSuccess="submissionAlertState.isSuccess"
                :message="submissionAlertState.message"
            />
            <DialogLeadDetail
                :open="isDetailDialogOpen"
                :selectedLead="selectedLead"
                @update:open="isDetailDialogOpen = $event"
            >
                <template #content>
                    <form @submit.prevent="handleAddNote">
                        <p>Notes:</p>
                        <textarea v-model="formAddNote.note" class="p-2 border-2 border-gray-300 rounded-lg w-full"></textarea>
                        <p class="text-red-500" v-if="formAddNote.errors.note">{{ formAddNote.errors.note }}</p>
                        <DialogFooter class="sm:justify-start">
                            <Button type="submit" variant="default" v-if="!isAddingNote">
                                <PlusIcon></PlusIcon> Add Note
                            </Button>
                            <Loader v-if="isAddingNote" class="animate-spin" />
                        </DialogFooter>
                    </form>
                </template>
            </DialogLeadDetail>
            <div class="grid grid-cols-5">
                <Link :href="route('staffs.leads.create')">
                    <Button variant="default" class="w-full text-white"><PlusCircle></PlusCircle>Add Leads</Button>
                </Link>
            </div>
            <div class="w-full rounded-lg border">
                <Table class="w-full">
                    <TableHeader class="text-[12pt]">
                        <TableRow class="bg-[#F5F5F4] dark:bg-[#1C1C1A]">
                            <TableHead>Name</TableHead>
                            <TableHead>Mobile</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="lead in pagination.data" v-bind:key="lead?.id">
                            <TableCell @click="() => handleOpenDialog(lead)" class="cursor-pointer">
                                <u class="text-blue-500">{{ lead.name }}</u>
                            </TableCell>
                            <TableCell>
                                {{ lead.mobile_number }}
                            </TableCell>
                            <TableCell>
                                {{ lead.email }}
                            </TableCell>
                            <TableCell>
                                <GroupedSelect
                                    :selectedId="lead.lead_status_id"
                                    placeholder="Select lead status"
                                    :convertable="leadStatuses"
                                    @update:selected-id="(event) => handleSelectedStatusChange(lead.id, event)"
                                    v-bind:key="lead.id"
                                />
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="pagination.data.length < 1">
                            <TableCell :colspan="4" class="text-center w-full">
                                Your leads bucket empty
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="flex w-full justify-center">
                <div class="w-full max-w-[14rem]">
                    <TablePagination
                        :current_page="pagination.current_page"
                        :last_page="pagination.last_page"
                        :per_page="pagination.per_page"
                        :next="next"
                        :prev="prev"
                        @update:current_page="pagination.current_page = $event"
                        @update:per_page="pagination.per_page = $event"
                        @update:last_page="pagination.last_page = $event"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
