<script setup>
import { Head } from "@inertiajs/vue3";
import { mdiMenu, mdiArrowLeftBoldOutline } from "@mdi/js";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionMain from "@/Components/SectionMain.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";

const props = defineProps({
    item: {
        type: Object,
        default: () => ({}),
    },
});


</script>

<template>
    <LayoutAuthenticated>
        <Head title="Show Item" />
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiMenu"
                title="Show Item"
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
                @submit.prevent=""
            >
                <FormField
                    label="Name"
                >
                    <FormControl
                        v-model="props.item.name"
                        type="text"
                        placeholder="Name"
                    >
                    </FormControl>
                </FormField>
                <FormField label="Item Price">
                    <FormControl
                        v-model="props.item.price"
                        type="text"
                        placeholder="Item Price"
                    >
                    </FormControl>
                </FormField>
                <template #footer>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
