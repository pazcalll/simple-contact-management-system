<script setup lang="ts">
import { Ref, ref, watch } from 'vue';
import Button from '../ui/button/Button.vue';
import { ChevronLeft } from 'lucide-vue-next';
import { ChevronRight } from 'lucide-vue-next';
import Popover from '../ui/popover/Popover.vue';
import PopoverTrigger from '../ui/popover/PopoverTrigger.vue';
import PopoverContent from '../ui/popover/PopoverContent.vue';
import Input from '../ui/input/Input.vue';
import { TPagination } from '@/types/custom';

const props = defineProps<{
    current_page: number;
    last_page: number;
    per_page: number;
    next: () => Promise<unknown>;
    prev: () => Promise<unknown>;
}>();

const currentPage = ref(props.current_page);
const perPage = ref(props.per_page);
const lastPage = ref(props.last_page);

watch(
  () => [props.current_page, props.per_page, props.last_page],
  ([newCurrent, newPer, newLast]) => {
    currentPage.value = newCurrent;
    perPage.value = newPer;
    lastPage.value = newLast;
  }
);

const handleCurrentPageInput = (e: InputEvent) => {
    e.preventDefault();

    const input = e.target as HTMLInputElement;
    const page = parseInt(input.value);

    if (isNaN(page)) currentPage.value = 1;
    else if (page < 1) currentPage.value = 1;
    else if (page > lastPage.value) currentPage.value = lastPage.value;
    else currentPage.value = page;
};

const handleDataPerPageInput = (e: InputEvent) => {
    e.preventDefault();

    const input = e.target as HTMLInputElement;
    const total = parseInt(input.value);

    if (isNaN(total)) perPage.value = 15;
    else if (total < 1) perPage.value = 1;
    else if (total > 100) perPage.value = 100;
    else perPage.value = total;
};
</script>

<template>
    <div class="flex justify-evenly">
        <Button @click="prev" variant="secondary" class="cursor-pointer"><ChevronLeft /></Button>
        <Button variant="secondary" class="cursor-pointer">
            <Popover>
                <PopoverTrigger class="cursor-pointer">
                    {{ currentPage }}
                    of
                    {{ lastPage }}
                    pages
                </PopoverTrigger>
                <PopoverContent class="mt-3 mb-3 h-[8rem] w-[14rem]">
                    <table class="h-full w-full">
                        <tbody>
                            <tr>
                                <td>
                                    <Label for="page">Page</Label>
                                </td>
                                <td class="px-3">:</td>
                                <td class="max-w-[4rem]">
                                    <Input name="page" type="number" v-model="currentPage" @input="handleCurrentPageInput" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <Label for="total">Data Per Page</Label>
                                </td>
                                <td class="px-3">:</td>
                                <td class="max-w-[4rem]">
                                    <Input name="total" type="number" v-model="perPage" @input="handleDataPerPageInput" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </PopoverContent>
            </Popover>
        </Button>
        <Button @click="next" variant="secondary" class="cursor-pointer"><ChevronRight /></Button>
    </div>
</template>

<script lang="ts">
const handleGetData = async (url: string): Promise<unknown> => {
    const fetcher = await fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-Request-Format': 'json',
        }
    });

    let data = null;
    if (fetcher.ok) {
        data = await fetcher.json() as TPagination<unknown>;
        return data;
    }

    return data;
}

const handleReloadAJAX = async (props: { pagination: Ref<TPagination<unknown>>; endpoint: string }): Promise<unknown> => {
    const url = props.endpoint + '?' + "page=" + (props.pagination.value.current_page ?? 1) + "&length=" + (props.pagination.value.per_page);
    const data = await handleGetData(url);

    return data;
}

const handleNextAJAX = async (props: { pagination: Ref<TPagination<unknown>>; endpoint: string }): Promise<unknown> => {
    if (props.pagination.value.current_page === props.pagination.value.last_page) return;
    const url = props.endpoint + '?' + "page=" + (props.pagination.value.current_page + 1) + "&length=" + (props.pagination.value.per_page);
    const data = await handleGetData(url);

    return data;
}

const handlePrevAJAX = async (props: { pagination: Ref<TPagination<unknown>>; endpoint: string }): Promise<unknown> => {
    if (props.pagination.value.current_page === 1) return;
    const url = props.endpoint + '?' + "page=" + (props.pagination.value.current_page - 1) + "&length=" + (props.pagination.value.per_page);
    const data = await handleGetData(url);

    return data;
}

export { handleNextAJAX, handlePrevAJAX, handleReloadAJAX };
</script>
