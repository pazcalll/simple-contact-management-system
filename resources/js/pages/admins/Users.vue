<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'
import { ref } from 'vue'
import { TPagination, TUser } from '@/types/custom';
import TablePagination, { handleNext, handlePrev } from '@/components/custom/TablePagination.vue';

const props = defineProps<{users: TPagination<TUser[]>}>()

const pagination = ref(props.users);
const next = () => handleNext({pagination: pagination, endpoint: '/admins/users'});
const prev = () => handlePrev({pagination: pagination, endpoint: '/admins/users'});
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div> -->
            </div>
            <div class="relative min-h-[100vh] flex-1 md:min-h-min">
                <div class="border rounded-lg w-full">
                    <Table class="w-full">
                        <TableHeader class="text-[12pt]">
                            <TableRow class="bg-[#F5F5F4] dark:bg-[#1C1C1A]">
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Role</TableHead>
                                <TableHead>Mobile</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in users.data" :key="user.id">
                                <TableCell>{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>{{ user.roles[0].name }}</TableCell>
                                <TableCell>{{ user.mobile_number }}</TableCell>
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
        </div>
    </AppLayout>
</template>
