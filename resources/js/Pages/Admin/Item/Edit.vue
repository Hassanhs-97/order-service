<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { mdiMenu, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";

const props = defineProps({
    item: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    _method: "put",
    name: props.item.name,
    price: props.item.price,
});
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Update Item" />
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiMenu"
                title="Update Item"
                main
            >
                <BaseButton
                    :route-name="route('admin.items.index')"
                    :icon="mdiArrowLeftBoldOutline"
                    label="Back"
                    color="white"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>
            <CardBox
                form
                @submit.prevent="
                    form.post(route('admin.items.update', props.item.id))
                "
            >
                <FormField
                    label="Name"
                    :class="{ 'text-red-400': form.errors.name }"
                >
                    <FormControl
                        v-model="form.name"
                        type="text"
                        placeholder="Enter Name"
                        :error="form.errors.name"
                    >
                        <div
                            class="text-red-400 text-sm"
                            v-if="form.errors.name"
                        >
                            {{ form.errors.name }}
                        </div>
                    </FormControl>
                </FormField>
                <FormField label="Item Price">
                    <div>{{ props.item.price }}</div>
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
