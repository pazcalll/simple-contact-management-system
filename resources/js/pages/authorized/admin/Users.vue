<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { ref } from 'vue';
import { TFlash, TPagination, TUser } from '@/types/custom';
import Button from '@/components/ui/button/Button.vue';
import { UserIcon } from 'lucide-vue-next';
import { router, usePage } from '@inertiajs/vue3';
import Alert from '@/components/ui/alert/Alert.vue';
import AlertTitle from '@/components/ui/alert/AlertTitle.vue';
import AlertDescription from '@/components/ui/alert/AlertDescription.vue';
import TablePaginationAjax, { handleNextAJAX, handlePrevAJAX } from '@/components/custom/table/TablePaginationAjax.vue';

const props = defineProps<{ users: TPagination<TUser[]> }>();
const page = usePage<TFlash>();

const pagination = ref(props.users);

const nextAjax = async () => {
  const response = await handleNextAJAX({ pagination: pagination, endpoint: '/admins/users' });
  if (response == undefined) return;
  pagination.value = response as TPagination<TUser[]>;
};

const prevAjax = async () => {
  const response = await handlePrevAJAX({ pagination: pagination, endpoint: '/admins/users' });
  if (response == undefined) return;
  pagination.value = response as TPagination<TUser[]>;
};

const handleAddUser = (e: Event) => {
  e.preventDefault();
  router.visit('/admins/users/create');
};
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
      <div class="grid auto-rows-min gap-4 md:grid-cols-6">
        <Button @click="handleAddUser" variant="default" class="text-white"><UserIcon></UserIcon>Add User</Button>
      </div>
      <div class="relative min-h-[100vh] flex-1 md:min-h-min">
        <div class="w-full rounded-lg border">
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
              <TableRow v-for="user in pagination.data" :key="user.id">
                <TableCell>{{ user.name }}</TableCell>
                <TableCell>{{ user.email }}</TableCell>
                <TableCell>{{ user.roles[0]?.name }}</TableCell>
                <TableCell>{{ user.mobile }}</TableCell>
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
    </div>
  </AppLayout>
</template>
