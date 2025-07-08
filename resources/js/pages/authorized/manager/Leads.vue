<script lang="ts" setup>
import SubmissionAlert, { TSubmissionAlert } from '@/components/custom/alert/SubmissionAlert.vue';
import BulkAssignDialog from '@/components/custom/dialog/BulkAssignDialog.vue';
import DialogLeadDetail from '@/components/custom/dialog/DialogLeadDetail.vue';
import GroupedSelect from '@/components/custom/select/GroupedSelect.vue';
import ItemSelect from '@/components/custom/select/ItemSelect.vue';
import TablePagination, { handleNext, handlePrev } from '@/components/custom/table/TablePagination.vue';
import Button from '@/components/ui/button/Button.vue';
import DialogFooter from '@/components/ui/dialog/DialogFooter.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Table from '@/components/ui/table/Table.vue';
import TableBody from '@/components/ui/table/TableBody.vue';
import TableCell from '@/components/ui/table/TableCell.vue';
import TableHead from '@/components/ui/table/TableHead.vue';
import TableHeader from '@/components/ui/table/TableHeader.vue';
import TableRow from '@/components/ui/table/TableRow.vue';
import { getLeadNotesJson } from '@/data/managers/lead-notes';
import AppLayout from '@/layouts/AppLayout.vue';
import { TFlash, TLead, TLeadNote, TLeadStatus, TPagination, TUser } from '@/types/custom';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Loader, PlusCircle, PlusIcon } from 'lucide-vue-next';
import { AcceptableValue } from 'reka-ui';
import { ref } from 'vue';

const page = usePage<{
  auth: TUser;
  flash: TFlash;
  leads: TPagination<TLead[]>;
  leadStatuses: TLeadStatus[];
  supervisor: TUser[];
}>();

const supervisors = ref<TUser[]>(page.props.supervisor);

const pagination = ref<TPagination<TLead[]>>(page.props.leads);
const isDetailDialogOpen = ref<boolean>(false);
const isAddingNote = ref<boolean>(false);
const submissionAlertState = ref<TSubmissionAlert>({
  isShow: false,
  message: null,
});

const isDialogOpen = ref<boolean>(false);
const checkedIds = ref<number[]>([]);
const selectedLeadStatusId = ref<number | null>(null);
const selectedSupervisorId = ref<number | null>(null);

const selectedLead = ref<TLead | null>(null);

const leadStatuses = page.props.leadStatuses;
const formAddNote = useForm({
  note: null,
});

const formBulkAssign = useForm<{
  lead_status_id: number | null;
  lead_ids: number[];
}>({
  lead_status_id: null,
  lead_ids: [],
});

const handleAddNote = async () => {
  isAddingNote.value = true;
  formAddNote.submit('post', `/managers/leads/${selectedLead.value?.id}/lead-notes`, {
    onBefore: (data) => {
      console.log(data);
    },
    onSuccess: () => {
      isDetailDialogOpen.value = false;
    },
    onFinish: () => {
      isAddingNote.value = false;
    },
  });
};

const handleLeadStatusId = async (statusId: AcceptableValue) => {
  const status = leadStatuses.find((leadStatus) => leadStatus.id == (statusId as number));
  selectedLeadStatusId.value = status?.id as number | null;
  console.log(selectedLeadStatusId.value);
};

const enqueueId = (id: number) => {
  if (!checkedIds.value) {
    checkedIds.value = [id];
  } else if (!checkedIds.value.includes(id)) {
    checkedIds.value = [...checkedIds.value, id];
  }
};

const dequeueId = (id: number) => {
  if (checkedIds.value) {
    checkedIds.value = checkedIds.value.filter((item) => item !== id);
    if (checkedIds.value.length === 0) {
      checkedIds.value = [];
    }
  }
};

const isQueued = (id: number) => {
  return checkedIds.value.includes(id);
};

const handleCheckbox = (id: number) => {
  const isQueuedVal = isQueued(id);
  if (isQueuedVal) dequeueId(id);
  else if (!isQueuedVal) enqueueId(id);
  else console.log('unidentified action');
};

const handleGetNotes = async () => {
  const response = await getLeadNotesJson(selectedLead.value?.id ?? 0);
  if (response && typeof response === 'object' && 'message' in response) {
    console.log(response);
    return;
  }
  return response as TLeadNote[];
};

const handleSelectedStatusChange = (leadId: number, event: AcceptableValue) => {
  const toUpdateLead = pagination.value.data.find((lead) => lead.id === leadId);
  const form = useForm({
    lead_id: toUpdateLead?.id,
    lead_status_id: event as number,
  });
  form.patch(`/managers/leads/${toUpdateLead?.id}/status`, {
    preserveScroll: true,
    preserveState: true,
    onError: (err) => {
      console.log(err);
    },
    onSuccess: (data) => {
      submissionAlertState.value.isShow = true;
      const flash = (data.props as unknown as TFlash).flash;
      submissionAlertState.value.message = flash.success || 'Lead status updated successfully';
    },
  });
};

const handleSubmitBulkAssign = () => {
  formBulkAssign.lead_ids = checkedIds.value;
  formBulkAssign.lead_status_id = selectedLeadStatusId.value;
  formBulkAssign.patch('/managers/leads/mass-update-status', {
    preserveState: false,
    onError: (err) => {
      console.log(err);
    },
    onSuccess: (data) => {
      submissionAlertState.value.isShow = true;
      const flash = (data.props as unknown as TFlash).flash;
      submissionAlertState.value.message = flash.success || 'Bulk action completed successfully';
      isDialogOpen.value = false;
      checkedIds.value = [];
      selectedLeadStatusId.value = null;
    },
  });
};

const handleOpenDialog = async (lead: TLead) => {
  selectedLead.value = lead;
  console.log(lead);

  isDetailDialogOpen.value = true;
  if (selectedLead.value) selectedLead.value.lead_notes = await handleGetNotes();
};

const next = () => handleNext({ pagination: pagination, endpoint: '/admins/leads' });
const prev = () => handlePrev({ pagination: pagination, endpoint: '/admins/leads' });
</script>

<template>
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <SubmissionAlert
        :isShow="submissionAlertState.isShow"
        :message="submissionAlertState.message"
        @update:is-show="submissionAlertState.isShow = $event"
      />
      <DialogLeadDetail :open="isDetailDialogOpen" :selectedLead="selectedLead" @update:open="isDetailDialogOpen = $event">
        <template #content>
          <form @submit.prevent="handleAddNote">
            <p>Notes:</p>
            <textarea v-model="formAddNote.note" class="w-full rounded-lg border-2 border-gray-300 p-2"></textarea>
            <p class="text-red-500" v-if="formAddNote.errors.note">{{ formAddNote.errors.note }}</p>
            <DialogFooter class="sm:justify-start">
              <Button type="submit" variant="default" v-if="!isAddingNote"> <PlusIcon></PlusIcon> Add Note </Button>
              <Loader v-if="isAddingNote" class="animate-spin" />
            </DialogFooter>
          </form>
        </template>
      </DialogLeadDetail>
      <div class="grid grid-cols-5 gap-2">
        <Link :href="route('managers.leads.create')">
          <Button variant="default" class="w-full text-white"><PlusCircle></PlusCircle>Add Leads</Button>
        </Link>
        <BulkAssignDialog @submit="handleSubmitBulkAssign" v-model:open="isDialogOpen">
          <template #trigger>
            <Button variant="secondary" class="w-full"> <PlusSquare></PlusSquare>Bulk Action</Button>
          </template>
          <template #content>
            <div class="w-full">
              <Label class="mb-1">Status</Label>
              <GroupedSelect
                v-model:selected-id="selectedLeadStatusId"
                placeholder="Select lead status"
                :convertable="leadStatuses"
                @update:selected-id="handleLeadStatusId"
              />
              <p class="text-red-500" v-if="formAddNote.errors.note">{{ formAddNote.errors.note }}</p>
            </div>
            <div class="w-full">
              <Label class="mb-1">Supervisor</Label>
              <ItemSelect placeholder="Select supervisor" :items="supervisors" @update:selected-id="selectedSupervisorId = $event as number" />
              <p class="text-red-500" v-if="formAddNote.errors.note">{{ formAddNote.errors.note }}</p>
            </div>
          </template>
        </BulkAssignDialog>
      </div>
      <div class="w-full rounded-lg border">
        <Table class="w-full">
          <TableHeader class="text-[12pt]">
            <TableRow class="bg-[#F5F5F4] dark:bg-[#1C1C1A]">
              <TableHead>#</TableHead>
              <TableHead>Name</TableHead>
              <TableHead>Mobile</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Status</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="lead in pagination.data" v-bind:key="lead?.id">
              <TableCell>
                <Input type="checkbox" class="cursor-pointer" @click="handleCheckbox(lead.id)" :checked="isQueued(lead.id)" />
              </TableCell>
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
              <TableCell :colspan="4" class="w-full text-center"> Your leads bucket empty </TableCell>
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
