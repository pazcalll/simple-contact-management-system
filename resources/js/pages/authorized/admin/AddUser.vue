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
import { User } from '@/types';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import { TRole } from '@/types/custom';

const page = usePage();
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    mobile: '',
    role_id: '',
    upline_id: '',
});
const roles = page.props.roles as TRole[];
const users = ref<User[]>([]);
const selectedRole = ref<TRole>();
const selectedUpline = ref<User>();

const handlerSubmit = async () => {
    await form.submit('post', '/admins/users');
};

const handlerRoleSelected = async (value: unknown) => {
    const role = roles.filter((item) => item.id === value)[0];
    selectedRole.value = role;
    if (role.upline) {
        const possibleUsers = (await getUsersByRole(role.upline?.id + '')) as User[];
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
                            <h2 class="text-2xl font-bold">Add User</h2>
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
                            <div class="mx-3 grid w-full grid-cols-2 gap-4">
                                <Button variant="default" type="submit"> Create </Button>
                                <Button type="reset"> Reset </Button>
                            </div>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
