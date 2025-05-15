<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    mobile: ''
});

const handlerSubmit = async () => {
    await form.submit('post', '/admins/users')
}
</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="w-full mx-0 flex justify-center">
                <form @submit.prevent="handlerSubmit">
                    <Card class="block w-[26rem] md:w-[40rem]">
                        <CardHeader class="w-full text-center">
                            <h2 class="font-bold text-2xl">Add User</h2>
                        </CardHeader>
                        <CardContent>
                            <div class="mx-3 space-y-2 my-5">
                                <Label>
                                    Name
                                </Label>
                                <Input v-model="form.name" />
                                <div class="text-red-500" v-if="form.errors.name">{{ form.errors.name }}</div>
                            </div>
                            <div class="mx-3 space-y-2 my-5">
                                <Label>
                                    Email
                                </Label>
                                <Input v-model="form.email" />
                                <div class="text-red-500" v-if="form.errors.email">{{ form.errors.email }}</div>
                            </div>
                            <div class="mx-3 space-y-2 my-5">
                                <Label>
                                    Password
                                </Label>
                                <Input v-model="form.password" type="password" />
                                <div class="text-red-500" v-if="form.errors.password">{{ form.errors.password }}</div>
                            </div>
                            <div class="mx-3 space-y-2 my-5">
                                <Label>
                                    Password Confirmation
                                </Label>
                                <Input v-model="form.password_confirmation" type="password" />
                                <div class="text-red-500" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</div>
                            </div>
                            <div class="mx-3 space-y-2 my-5">
                                <Label>
                                    Mobile
                                </Label>
                                <Input v-model="form.mobile" />
                                <div class="text-red-500" v-if="form.errors.mobile">{{ form.errors.mobile }}</div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <div class="w-full gap-4 mx-3 grid grid-cols-2">
                                <Button variant="default" type="submit">
                                    Create
                                </Button>
                                <Button type="reset">
                                    Reset
                                </Button>
                            </div>
                        </CardFooter>
                    </Card>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
