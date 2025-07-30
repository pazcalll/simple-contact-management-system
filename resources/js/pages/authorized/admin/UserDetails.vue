<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';
import { getUsersByRole } from '@/data/admins/users';
import { ref } from 'vue';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import { TRole, TUser } from '@/types/custom';
import { toast } from 'vue-sonner';

const page = usePage<{
  user: TUser;
  roles: TRole[];
  upline: TUser | undefined;
  possibleUplines: TUser[];
}>();
const form = useForm({
  name: page.props.user?.name || '',
  email: page.props.user?.email || '',
  password: '',
  password_confirmation: '',
  mobile: page.props.user?.mobile || '',
  role_id: page.props.user?.roles[0]?.id || '',
  upline_id: page.props.user?.upline_id || '',
});

const roles = page.props.roles as TRole[];
const users = ref<TUser[]>(page.props.possibleUplines || []);
const selectedRole = ref<TRole>(page.props.user?.roles[0]);
const selectedUpline = ref<TUser | undefined>(page.props.upline);

const handlerSubmit = () => {
  form.submit('patch', '/admins/users/' + page.props.user.id, {
    onError: (errors) => {
      let message = '';
      for (const [, value] of Object.entries(errors)) {
        message = `${value}`;
      }
      toast.error(message, {
        duration: 6000,
        class: '!bg-red-500 !text-white',
      });
    },
  });
};

const handlerRoleSelected = async (value: unknown) => {
  const role = roles.filter((item) => item.id === value)[0];
  selectedRole.value = role;
  if (role.upline_id) {
    const possibleUsers = (await getUsersByRole(role.upline_id + '')) as TUser[];
    users.value = possibleUsers;
  }
  form.upline_id = '';
  selectedUpline.value = undefined;
};
</script>

<template>
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="mx-0 flex w-full justify-center">
        <form @submit.prevent="handlerSubmit">
          <Card class="block w-[26rem] md:w-[40rem]">
            <CardHeader class="w-full text-center">
              <h2 class="text-2xl font-bold">Update User</h2>
            </CardHeader>
            <CardContent>
              <div class="mx-3 my-5 space-y-2">
                <Label> Name </Label>
                <Input v-model="form.name" />
                <div class="text-xs text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Email </Label>
                <Input v-model="form.email" type="email" />
                <div class="text-xs text-red-500" v-if="form.errors.email">{{ form.errors.email }}</div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Password </Label>
                <Input v-model="form.password" type="password" />
                <div class="text-xs text-red-500" v-if="form.errors.password">{{ form.errors.password }}</div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Password Confirmation </Label>
                <Input v-model="form.password_confirmation" type="password" />
                <div class="text-xs text-red-500" v-if="form.errors.password_confirmation">
                  {{ form.errors.password_confirmation }}
                </div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Mobile </Label>
                <Input v-model="form.mobile" type="number" />
                <div class="text-xs text-red-500" v-if="form.errors.mobile">{{ form.errors.mobile }}</div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Role </Label>
                <Select v-model="form.role_id" @update:model-value="handlerRoleSelected">
                  <SelectTrigger class="w-full cursor-pointer">
                    <SelectValue placeholder="Select a role" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectGroup>
                      <SelectItem v-for="(role, index) in roles" :key="index" :value="role.id">
                        {{ role.name }}
                      </SelectItem>
                    </SelectGroup>
                  </SelectContent>
                </Select>
                <div class="text-xs text-red-500" v-if="form.errors.role_id">{{ form.errors.role_id }}</div>
              </div>
              <div
                class="mx-3 my-5 space-y-2"
                v-if="selectedRole?.name !== 'admin' && selectedRole !== undefined && selectedRole?.name !== 'manager'"
              >
                <Label> Upline </Label>
                <Select v-model="form.upline_id">
                  <SelectTrigger class="w-full cursor-pointer">
                    <SelectValue placeholder="Select a upline" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectGroup>
                      <SelectItem v-for="(user, index) in users" :key="index" :value="user.id">
                        {{ user.name }}
                      </SelectItem>
                    </SelectGroup>
                  </SelectContent>
                </Select>
                <div class="text-xs text-red-500" v-if="form.errors.upline_id">{{ form.errors.upline_id }}</div>
              </div>
            </CardContent>
            <CardFooter>
              <div class="mx-3 flex w-full justify-center gap-4">
                <Button variant="default" class="w-[50%]" type="submit"> Update </Button>
              </div>
            </CardFooter>
          </Card>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
