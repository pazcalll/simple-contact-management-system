<script lang="ts" setup>
import SubmissionAlert, { TSubmissionAlert } from '@/components/custom/alert/SubmissionAlert.vue';
import Button from '@/components/ui/button/Button.vue';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardFooter from '@/components/ui/card/CardFooter.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const submissionAlertState = ref<TSubmissionAlert>({
  message: null,
  isShow: false,
});

const form = useForm({
  name: '',
  email: '',
  mobile_number: '',
  utm_source: '',
  utm_medium: undefined,
  utm_campaign: undefined,
});

const handlerSubmit = () => {
  try {
    form.submit('post', '/staffs/leads', {
      onSuccess: () => {
        submissionAlertState.value.isShow = true;
        submissionAlertState.value.message = 'Lead created successfully.';
        form.reset();
      },
    });
  } catch (e) {
    console.log(e);
    alert('Cant submit data');
  }
};
</script>

<template>
  <AppLayout>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <SubmissionAlert
        :is-show="submissionAlertState.isShow"
        :message="submissionAlertState.message"
        @update:is-show="submissionAlertState.isShow = $event"
      />
      <div class="mx-0 flex w-full justify-center">
        <form @submit.prevent="handlerSubmit">
          <Card class="block w-[26rem] border-0 shadow-none md:w-[40rem]">
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
                <Input v-model="form.utm_source" type="text" />
                <div class="text-xs text-red-500" v-if="form.errors.utm_source">{{ form.errors.utm_source }}</div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Medium </Label>
                <Input v-model="form.utm_medium" type="text" />
                <div class="text-xs text-red-500" v-if="form.errors.utm_medium">{{ form.errors.utm_medium }}</div>
              </div>
              <div class="mx-3 my-5 space-y-2">
                <Label> Campaign </Label>
                <Input v-model="form.utm_campaign" type="text" />
                <div class="text-xs text-red-500" v-if="form.errors.utm_campaign">{{ form.errors.utm_campaign }}</div>
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
