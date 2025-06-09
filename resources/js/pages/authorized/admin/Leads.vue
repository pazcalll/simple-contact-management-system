<script setup lang="ts">
import BulkAssignDialog from '@/components/custom/admin/BulkAssignDialog.vue';
import TablePaginationAjax, { handleNextAJAX, handlePrevAJAX, handleReloadAJAX } from '@/components/custom/TablePaginationAjax.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { getUsersByUpline } from '@/data/admins/users';
import { updateLeadStatus } from '@/data/leads';
import AppLayout from '@/layouts/AppLayout.vue';
import { TFlash, TLead, TLeadStatus, TPagination, TUser } from '@/types/custom';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Loader, PlusCircle, PlusSquare } from 'lucide-vue-next';
import { AcceptableValue } from 'reka-ui';
import { onMounted, ref } from 'vue';

type TLeadWithUsers = TLead & {
    users?: TUser[]|null
};

const page = usePage<TFlash>();
const pagination = ref<TPagination<TLeadWithUsers[]>>(page?.props?.leads as TPagination<TLeadWithUsers[]>);
const leadStatuses = ref<TLeadStatus[]>(page.props.leadStatuses as TLeadStatus[]);
const groupedLeadStatuses = ref<{ [group: string]: TLeadStatus[] }>({});
const isDialogOpen = ref<boolean>(false);
const statusLoading = ref<{ [leadId: number]: boolean }>({});
const isUnassign = ref<'on'|'off'>('off');

const managers = ref<TUser[]>([]);
const supervisors = ref<TUser[]>([]);
const teamLeaders = ref<TUser[]>([]);
const staffs = ref<TUser[]>([]);

const checkedIds = ref<number[]>([]);
const selectedLeadStatusId = ref<number|null>(null);
const selectedManagerId = ref<number|null>(null);
const selectedSupervisorId = ref<number|null>(null);
const selectedTeamLeaderId = ref<number|null>(null);
const selectedStaffId = ref<number|null>(null);

const groupLeadStatuses = () => {
    const groups: { [group: string]: TLeadStatus[] } = {};
    leadStatuses.value.forEach((status) => {
        const group = status.group || 'Ungrouped';
        if (!groups[group]) {
            groups[group] = [];
        }
        groups[group].push(status);
    });
    groupedLeadStatuses.value = groups;
};

onMounted(() => {
    managers.value = (page.props.managers || []) as TUser[];
    groupLeadStatuses();
});

const enqueueId = (id: number) => {
    if (!checkedIds.value) {
        checkedIds.value = [id];
    } else if (!checkedIds.value.includes(id)) {
        checkedIds.value = [...checkedIds.value, id];
    }
};

const dequeueId = (id: number) => {
    if (checkedIds.value) {
        checkedIds.value = checkedIds.value.filter(item => item !== id);
        if (checkedIds.value.length === 0) {
            checkedIds.value = [];
        }
    }
};

const isQueued = (id: number) => {
    return checkedIds.value.includes(id);
}

const handleCheckbox = (id: number) => {
    const isQueuedVal = isQueued(id);
    if (isQueuedVal) dequeueId(id);
    else if (!isQueuedVal) enqueueId(id);
    else console.log('unidentified action');
}

const handleLeadStatusId = async (statusId: AcceptableValue) => {
    const status = leadStatuses.value.find(leadStatus => leadStatus.id == statusId as number)
    selectedLeadStatusId.value = status?.id as number|null;
}

const handleManagerSelected = async (managerId: AcceptableValue) => {
    const dataSupervisors = await getUsersByUpline(managerId as string)
    supervisors.value = dataSupervisors as TUser[]
    teamLeaders.value = []
    staffs.value = []
}

const handleSupervisorSelected = async (supervisorId: AcceptableValue) => {
    const dataTeamLeaders = await getUsersByUpline(supervisorId as string)
    teamLeaders.value = dataTeamLeaders as TUser[]
    staffs.value = []
}

const handleTeamLeaderSelected = async (TeamLeaderId: AcceptableValue) => {
    const dataStaffs = await getUsersByUpline(TeamLeaderId as string)
    staffs.value = dataStaffs as TUser[]
}

const handleLeadStatusChanged = async (leadId: number, leadStatusId: AcceptableValue) => {
    statusLoading.value[leadId] = true;
    await updateLeadStatus(leadId, leadStatusId as number)
    const data = await handleReloadAJAX({pagination: pagination, endpoint: '/admins/leads'});
    pagination.value = data as TPagination<TLead[]>;
    statusLoading.value[leadId] = false;
}

const handleUnassignChecked = (e: Event) => {
    const checked = (e.target as HTMLInputElement).checked;
    isUnassign.value = checked ? 'on' : 'off';
}

const handleSubmitBulkAssign = () => {
    const form = useForm({
        lead_ids: checkedIds.value,
        lead_status_id: selectedLeadStatusId.value,
        manager_id: selectedManagerId.value,
        supervisor_id: selectedSupervisorId.value,
        team_leader_id: selectedTeamLeaderId.value,
        staff_id: selectedStaffId.value,
        is_unassign: isUnassign.value,
    });

    form.submit('post', '/admins/leads/bulk-assign-leads', {
        onSuccess: async function () {
            pagination.value = pagination.value;
            isDialogOpen.value = false;

            checkedIds.value = [];
            selectedLeadStatusId.value = null;
            selectedManagerId.value = null;
            selectedSupervisorId.value = null;
            selectedTeamLeaderId.value = null;
            selectedStaffId.value = null;
            supervisors.value = [];
            teamLeaders.value = [];
            staffs.value = [];

            const data = await handleReloadAJAX({ pagination: pagination, endpoint: '/admins/leads' });
            pagination.value = data as TPagination<TLead[]>;
        }
    });
}

const nextAjax = async () => {
    const response = await handleNextAJAX({ pagination: pagination, endpoint: '/admins/leads' })
    if (response == undefined) return;
    pagination.value = response as TPagination<TLead[]>
}
const prevAjax = async () => {
    const response = await handlePrevAJAX({ pagination: pagination, endpoint: '/admins/leads' })
    if (response == undefined) return;
    pagination.value = response as TPagination<TLead[]>
}
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Alert v-if="page.props.flash.success" class="bg-green-500">
                <AlertTitle class="text-white">Success</AlertTitle>
                <AlertDescription class="text-white">
                    {{ page.props.flash.success }}
                </AlertDescription>
            </Alert>
            <div class="grid grid-cols-5 gap-2">
                <Link :href="route('admins.leads.create')">
                    <Button variant="default" class="w-full text-white"> <PlusCircle></PlusCircle>Add Leads </Button>
                </Link>
                <BulkAssignDialog @submit="handleSubmitBulkAssign" v-model:open="isDialogOpen">
                    <template #trigger>
                        <Button variant="secondary" class="w-full"> <PlusSquare></PlusSquare>Bulk Assign </Button>
                    </template>
                    <template #content>
                        <div class="w-full">
                            <Label class="mb-1">Status</Label>
                            <Select v-model="selectedLeadStatusId" @update:model-value="handleLeadStatusId">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Lead Status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectGroup v-for="[index, groupedLeadStatus] in Object.entries(groupedLeadStatuses)" :key="index">
                                        <SelectLabel class="bg-gray-300">{{ index }}</SelectLabel>
                                        <SelectItem v-for="(leadStatus, index) in groupedLeadStatus" :key="index" :value="leadStatus.id">
                                            {{ leadStatus.name }}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>
                        <div v-if="isUnassign == 'off'" class="w-full space-y-3">
                            <div class="w-full">
                                <Label class="mb-1">Manager</Label>
                                <Select v-model="selectedManagerId" @update:model-value="handleManagerSelected">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Manager" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="manager in managers" :key="manager.id" :value="manager.id">
                                                {{ manager.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="w-full">
                                <Label class="mb-1">Supervisor</Label>
                                <Select v-model="selectedSupervisorId" @update:model-value="handleSupervisorSelected">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Supervisor"/>
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="supervisor in supervisors" :key="supervisor.id" :value="supervisor.id">
                                            {{ supervisor.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="w-full">
                                <Label class="mb-1">Team Leader</Label>
                                <Select v-model="selectedTeamLeaderId" @update:model-value="handleTeamLeaderSelected">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Team Leader"/>
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="teamLeader in teamLeaders" :key="teamLeader.id" :value="teamLeader.id">
                                            {{ teamLeader.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div class="w-full">
                                <Label class="mb-1">Staff</Label>
                                <Select v-model="selectedStaffId">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Select Staff"/>
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="staff in staffs" :key="staff.id" :value="staff.id">
                                            {{ staff.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex justify-center space-x-1">
                                <Input id="unassign" type="checkbox" class="w-[1rem]" @click="handleUnassignChecked" :checked="isUnassign == 'on'" />
                                <Label for="unassign">
                                    Unassign
                                </Label>
                            </div>
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
                            <TableHead>Ownership</TableHead>
                            <TableHead>Assignees</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="lead in pagination.data" v-bind:key="lead?.id">
                            <TableCell>
                                <Input type="checkbox" @click="handleCheckbox(lead.id)" :checked="isQueued(lead.id)" />
                            </TableCell>
                            <TableCell>
                                {{ lead.name }}
                            </TableCell>
                            <TableCell>
                                {{ lead.mobile_number }}
                            </TableCell>
                            <TableCell>
                                {{ lead.email }}
                            </TableCell>
                            <TableCell>
                                <Select v-if="!statusLoading[lead.id]" v-model="lead.lead_status_id" @update:model-value="(e) => handleLeadStatusChanged(lead.id, e)">
                                    <SelectTrigger
                                        class="w-full cursor-pointer"
                                        :style="{ borderColor: lead?.lead_status?.color, borderWidth: '2px' }"
                                    >
                                        <SelectValue class="px-1 font-extrabold" :placeholder="lead?.lead_status?.name" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup v-for="[index, groupedLeadStatus] in Object.entries(groupedLeadStatuses)" :key="index">
                                            <SelectLabel class="bg-gray-300">{{ index }}</SelectLabel>
                                            <SelectItem v-for="(leadStatus, index) in groupedLeadStatus" :key="index" :value="leadStatus.id">
                                                {{ leadStatus.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <Loader v-if="statusLoading[lead.id]" class="animate-spin" />
                            </TableCell>
                            <TableCell>
                                {{ lead.is_private ? 'owned by the staff' : 'owned by admin' }}
                            </TableCell>
                            <TableCell>
                                <ul class="list-disc">
                                    <li v-for="(user, _index) in lead.users" v-bind:key="_index">{{ user.name }}</li>
                                </ul>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="flex w-full justify-center">
                    <div class="w-full max-w-[14rem]">
                        <TablePaginationAjax
                            :current_page="pagination.current_page"
                            :last_page="pagination.last_page"
                            :per_page="pagination.per_page"
                            :next="nextAjax"
                            :prev="prevAjax"
                            @update:current_page="pagination.current_page = $event"
                            @update:per_page="pagination.per_page = $event"
                            @update:last_page="pagination.last_page = $event"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
