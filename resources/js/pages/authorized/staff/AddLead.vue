<script lang="ts" setup>
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    mobile_number: '',
    source: '',
    medium: undefined,
    campaign: undefined,
});

const handlerSubmit = () => {
    try {
        console.log('submission ');
        form.submit('post', '/staffs/leads');
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
                    <Card class="block w-[26rem] md:w-[40rem] border-0 shadow-none">
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
