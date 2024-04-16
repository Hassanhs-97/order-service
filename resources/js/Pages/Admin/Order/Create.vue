<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"
import {
  mdiMenu,
  mdiArrowLeftBoldOutline
} from "@mdi/js"
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue"
import SectionMain from "@/Components/SectionMain.vue"
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue"
import CardBox from "@/Components/CardBox.vue"
import FormField from '@/Components/FormField.vue'
import FormControl from '@/Components/FormControl.vue'
import BaseButton from '@/Components/BaseButton.vue'
import BaseButtons from '@/Components/BaseButtons.vue'

const form = useForm({
  customer_name: '',
  customer_address: ''

})
</script>

<template>
  <LayoutAuthenticated>
    <Head title="Create menu" />
    <SectionMain>
      <SectionTitleLineWithButton
        :icon="mdiMenu"
        title="Add menu"
        main
      >
        <BaseButton
          :route-name="route('admin.menu.index')"
          :icon="mdiArrowLeftBoldOutline"
          label="Back"
          color="white"
          rounded-full
          small
        />
      </SectionTitleLineWithButton>
      <CardBox
        form
        @submit.prevent="form.post(route('admin.menu.store'))"
      >
        <FormField
          label="Customer Name"
          :class="{ 'text-red-400': form.errors.customer_name }"
        >
          <FormControl
            v-model="form.customer_name"
            type="text"
            placeholder="Enter Customer Name"
            :error="form.errors.customer_name"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.customer_name">
              {{ form.errors.customer_name }}
            </div>
          </FormControl>
        </FormField>
        <FormField
          label="Customer Address"
          :class="{ 'text-red-400': form.errors.customer_address }"
        >
          <FormControl
            v-model="form.customer_address"
            type="text"
            placeholder="Enter Customer Address"
            :error="form.errors.customer_address"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.customer_address">
              {{ form.errors.customer_address }}
            </div>
          </FormControl>
        </FormField>
        <FormField
          label="Items"
          :class="{ 'text-red-400': form.errors.items }"
        >
          <FormControl
            v-model="form.items"
            type="select"
            placeholder="Add Items"
            :error="form.errors.items"
          >
            <div class="text-red-400 text-sm" v-if="form.errors.items">
              {{ form.errors.items }}
            </div>
          </FormControl>
        </FormField>
        <template #footer>
          <BaseButtons>
            <BaseButton
              type="submit"
              color="info"
              label="Submit"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            />
          </BaseButtons>
        </template>
      </CardBox>
    </SectionMain>
  </LayoutAuthenticated>
</template>
