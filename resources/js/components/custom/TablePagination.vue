<script setup lang="ts">
import { TPagination, TUser } from '@/types/custom';
import { defineProps, ref } from 'vue';
import Button from '../ui/button/Button.vue';
import { ChevronLeft } from 'lucide-vue-next';
import { ChevronRight } from 'lucide-vue-next';
import Popover from '../ui/popover/Popover.vue';
import PopoverTrigger from '../ui/popover/PopoverTrigger.vue';
import PopoverContent from '../ui/popover/PopoverContent.vue';
import Input from '../ui/input/Input.vue';

const props = defineProps<{pagination: TPagination<TUser[]>}>();

const currentPage = ref(props.pagination.current_page);
const total = ref(props.pagination.total)
</script>

<template>
    <div class="flex justify-evenly">
        <Button variant="secondary" class="cursor-pointer"><ChevronLeft/></Button>
        <Button variant="secondary" class="cursor-pointer">
            <Popover>
                <PopoverTrigger class="cursor-pointer">
                    {{ props.pagination.current_page }}
                    of
                    {{ props.pagination.total }}
                    pages
                </PopoverTrigger>
                <PopoverContent class="mt-3 mb-3 w-[14rem] h-[8rem]">
                    <table class="w-full h-full">
                        <tbody>
                            <tr>
                                <td>
                                    <Label for="page">Page</Label>
                                </td>
                                <td class="px-3">:</td>
                                <td class="max-w-[4rem]">
                                    <Input name="page" type="number" v-model="currentPage"/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <Label for="total">Total Data</Label>
                                </td>
                                <td class="px-3">:</td>
                                <td class="max-w-[4rem]">
                                    <Input name="total" type="number" v-model="total"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </PopoverContent>
            </Popover>
        </Button>
        <Button variant="secondary" class="cursor-pointer"><ChevronRight/></Button>
    </div>
</template>
