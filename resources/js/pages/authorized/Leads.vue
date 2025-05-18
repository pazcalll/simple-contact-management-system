<script setup lang="ts">
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
const leads = ref<TLead[]>();
const leadStatuses = ref<TLeadStatus[]>(page.props.leadStatuses as TLeadStatus[]);
leads.value = (page.props?.leads as TPagination<TLead[]>)?.data ?? [];
console.log(page.props.leads)
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
                            <TableHead>Is Private</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="lead in leads" v-bind:key="lead?.id">
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
            </div>
        </div>
    </AppLayout>
</template>
