<script lang="ts" setup>
import TablePagination, { handleNext, handlePrev } from '@/components/custom/TablePagination.vue';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import Button from '@/components/ui/button/Button.vue';
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
import AppLayout from '@/layouts/AppLayout.vue';
import { TFlash, TLead, TLeadStatus, TPagination } from '@/types/custom';
import { Link, usePage } from '@inertiajs/vue3';
import { PlusCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const page = usePage<TFlash>();
const pagination = ref<TPagination<TLead[]>>(page.props.leads as TPagination<TLead[]>);

const leadStatuses = page.props.leadStatuses as TLeadStatus[];

const next = () => handleNext({ pagination: pagination, endpoint: '/admins/leads' });
const prev = () => handlePrev({ pagination: pagination, endpoint: '/admins/leads' });
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
                        </TableRow>
                        <TableRow v-if="pagination.data.length < 1">
                            <TableCell :colspan="4" class="text-center w-full">
                                Your leads bucket empty
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
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
        </div>
    </AppLayout>
</template>
