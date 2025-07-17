<script setup lang="ts">
import TablePaginationAjax, { handleNextAJAX, handlePrevAJAX } from '@/components/custom/table/TablePaginationAjax.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { ROLE_MANAGER } from '@/consts/role';
import AppLayout from '@/layouts/AppLayout.vue';
import { TFlash, TLead, TPagination, TUser } from '@/types/custom';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

type TLeadWithUsers = TLead & {
  users?: TUser[] | null;
};

const page = usePage<
  TFlash & {
    customers: TPagination<TLeadWithUsers[]>;
  }
>();
const pagination = ref(page?.props?.customers);

const filterUsers = (users?: TUser[] | null) => {
  console.log(users);
  return users ? users.filter((user) => !user.roles.some((role) => role.name === ROLE_MANAGER)) : [];
};

const nextAjax = async () => {
  const response = await handleNextAJAX({ pagination: pagination, endpoint: '/supervisor/customers' });
  if (response == undefined) return;
  pagination.value = response as TPagination<TLead[]>;
};
const prevAjax = async () => {
  const response = await handlePrevAJAX({ pagination: pagination, endpoint: '/supervisor/customers' });
  if (response == undefined) return;
  pagination.value = response as TPagination<TLead[]>;
};
</script>

<template>
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="w-full rounded-lg border">
        <Table class="w-full">
          <TableHeader class="text-[12pt]">
            <TableRow class="bg-[#F5F5F4] dark:bg-[#1C1C1A]">
              <TableHead>Name</TableHead>
              <TableHead>Mobile</TableHead>
              <TableHead>Email</TableHead>
              <TableHead>Assignees</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="customer in pagination.data" v-bind:key="customer?.id">
              <TableCell>
                {{ customer.name }}
              </TableCell>
              <TableCell>
                {{ customer.mobile_number }}
              </TableCell>
              <TableCell>
                {{ customer.email }}
              </TableCell>
              <TableCell>
                <ul class="list-disc">
                  <li v-for="(user, _index) in filterUsers(customer.users)" :key="_index">
                    {{ user.name }}
                  </li>
                </ul>
              </TableCell>
            </TableRow>
            <TableRow v-if="pagination.data.length === 0">
              <TableCell colspan="4" class="text-center text-gray-500"> No customers found. </TableCell>
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
