<script lang="ts" setup>
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import { getUsersByUpline } from '@/data/admins/users';
import AppLayout from '@/layouts/AppLayout.vue';
import { TUser } from '@/types/custom';
import { useForm, usePage } from '@inertiajs/vue3';
import { AcceptableValue } from 'reka-ui';
import { ref } from 'vue';

const page = usePage();
const form = useForm({
    name: '',
    email: '',
    mobile_number: '',
    manager_id: '',
    supervisor_id: undefined,
    team_leader_id: undefined,
    staff_id: undefined,
    source: '',
    medium: undefined,
    campaign: undefined,
});
const managers = page.props.managers as TUser[];
const supervisors = ref<TUser[]>([]);
const teamLeaders = ref<TUser[]>([]);
const staffs = ref<TUser[]>([]);

const handlerUpdateManager = async (value: AcceptableValue) => {
    try {
        const data = (await getUsersByUpline(value as string)) as TUser[];
        supervisors.value = data;
    } catch (e) {
        console.log(e);
        alert('Cant get data');
    }
};

const handlerUpdateSupervisor = async (value: AcceptableValue) => {
    try {
        const data = (await getUsersByUpline(value as string)) as TUser[];
        teamLeaders.value = data;
    } catch (e) {
        console.log(e);
        alert('Cant get data');
    }
};
const handlerUpdateTeamLeader = async (value: AcceptableValue) => {
    try {
        const data = (await getUsersByUpline(value as string)) as TUser[];
        staffs.value = data;
    } catch (e) {
        console.log(e);
        alert('Cant get data');
    }
};

const handlerSubmit = () => {
    try {
        console.log('submission ');
        form.submit('post', '/admins/leads');
    } catch (e) {
        console.log(e);
        alert('Cant submit data');
    }
};
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mx-0 flex w-full justify-center">
                <form @submit.prevent="handlerSubmit">
                    <Card class="block w-[26rem] md:w-[40rem]">
                        <CardHeader class="w-full text-center">
                            <h2 class="text-2xl font-bold">Add Lead</h2>
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
                                <Label> Mobile </Label>
                                <Input v-model="form.mobile_number" type="text" />
                                <div class="text-xs text-red-500" v-if="form.errors.mobile_number">{{ form.errors.mobile_number }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Source </Label>
                                <Input v-model="form.source" type="text" />
                                <div class="text-xs text-red-500" v-if="form.errors.source">{{ form.errors.source }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Medium </Label>
                                <Input v-model="form.medium" type="text" />
                                <div class="text-xs text-red-500" v-if="form.errors.medium">{{ form.errors.medium }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Campaign </Label>
                                <Input v-model="form.campaign" type="text" />
                                <div class="text-xs text-red-500" v-if="form.errors.campaign">{{ form.errors.campaign }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Manager </Label>
                                <Select v-model="form.manager_id" @update:model-value="handlerUpdateManager">
                                    <SelectTrigger class="w-full cursor-pointer">
                                        <SelectValue placeholder="Select a upline" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="(manager, index) in managers" :key="index" :value="manager.id">
                                                {{ manager.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <div class="text-xs text-red-500" v-if="form.errors.manager_id">{{ form.errors.manager_id }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Supervisor </Label>
                                <Select v-model="form.supervisor_id" @update:model-value="handlerUpdateSupervisor">
                                    <SelectTrigger class="w-full cursor-pointer">
                                        <SelectValue placeholder="Select a upline" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="(supervisor, index) in supervisors" :key="index" :value="supervisor.id">
                                                {{ supervisor.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <div class="text-xs text-red-500" v-if="form.errors.supervisor_id">{{ form.errors.supervisor_id }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Team Leader </Label>
                                <Select v-model="form.team_leader_id" @update:model-value="handlerUpdateTeamLeader">
                                    <SelectTrigger class="w-full cursor-pointer">
                                        <SelectValue placeholder="Select a upline" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="(teamLeader, index) in teamLeaders" :key="index" :value="teamLeader.id">
                                                {{ teamLeader.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <div class="text-xs text-red-500" v-if="form.errors.team_leader_id">{{ form.errors.team_leader_id }}</div>
                            </div>
                            <div class="mx-3 my-5 space-y-2">
                                <Label> Staff </Label>
                                <Select v-model="form.staff_id">
                                    <SelectTrigger class="w-full cursor-pointer">
                                        <SelectValue placeholder="Select a upline" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="(staff, index) in staffs" :key="index" :value="staff.id">
                                                {{ staff.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <div class="text-xs text-red-500" v-if="form.errors.staff_id">{{ form.errors.staff_id }}</div>
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
