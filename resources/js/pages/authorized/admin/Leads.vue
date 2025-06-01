<script setup lang="ts">
import BulkAssignDialog from '@/components/custom/admin/BulkAssignDialog.vue';
import TablePaginationAjax, { handleNextAJAX, handlePrevAJAX } from '@/components/custom/TablePaginationAjax.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { TFlash, TLead, TLeadStatus, TPagination } from '@/types/custom';
import { Link, usePage } from '@inertiajs/vue3';
import { PlusCircle, PlusSquare } from 'lucide-vue-next';
import { ref } from 'vue';

const page = usePage<TFlash>();
const pagination = ref<TPagination<TLead[]>>(page?.props?.leads as TPagination<TLead[]>);
const leadStatuses = ref<TLeadStatus[]>(page.props.leadStatuses as TLeadStatus[]);
const checkedIds = ref<number[]>([]);

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
                <BulkAssignDialog>
                    <Button variant="secondary" class="w-full"> <PlusSquare></PlusSquare>Bulk Assign </Button>
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
                                <Select>
                                    <SelectTrigger
                                        class="w-full cursor-pointer"
                                        :style="{ borderColor: lead?.lead_status?.color, borderWidth: '2px' }"
                                    >
                                        <SelectValue class="px-1 font-extrabold" :placeholder="lead?.lead_status?.name" />
                                    </SelectTrigger>
                                    <SelectContent @change="console.log('clicked')">
                                        <SelectGroup>
                                            <SelectItem v-for="(leadStatus, index) in leadStatuses" :key="index" :value="leadStatus.id">
                                                {{ leadStatus.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </TableCell>
                            <TableCell>
                                {{ lead.is_private ? 'owned by the staff' : 'owned by admin' }}
                            </TableCell>
                            <TableCell>
                                _
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
