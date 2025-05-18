<script setup lang="ts">
import TablePagination, { handleNext, handlePrev } from '@/components/custom/TablePagination.vue';
import Button from '@/components/ui/button/Button.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue';
import { TLead, TLeadStatus, TPagination } from '@/types/custom';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const page = usePage();
const pagination = ref<TPagination<TLead[]>>(page?.props?.leads as TPagination<TLead[]>);
const leadStatuses = ref<TLeadStatus[]>(page.props.leadStatuses as TLeadStatus[]);

const next = () => handleNext({pagination: pagination, endpoint: '/admins/leads'});
const prev = () => handlePrev({pagination: pagination, endpoint: '/admins/leads'});
console.log(pagination)
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid grid-cols-5">
                <Button>
                    Add Leads
                </Button>
            </div>
            <div class="border rounded-lg w-full">
                <Table class="w-full">
                    <TableHeader class="text-[12pt]">
                        <TableRow class="bg-[#F5F5F4] dark:bg-[#1C1C1A]">
                            <TableHead>Name</TableHead>
                            <TableHead>Mobile</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Ownership</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="lead in pagination.data" v-bind:key="lead?.id">
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
                                    <SelectTrigger class="w-full cursor-pointer" :style="{ backgroundColor: lead?.lead_status?.color }">
                                        <SelectValue class="text-black font-black font-extrabold" :placeholder="lead?.lead_status?.name"/>
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
                        </TableRow>
                    </TableBody>
                </Table>
                <div class="w-full flex justify-center">
                    <div class="max-w-[14rem] w-full">
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
        </div>
    </AppLayout>
</template>
