<script setup>
import { Head } from "@inertiajs/vue3";
import {
    mdiAccountMultiple,
    mdiInvoice,
    mdiChartTimelineVariant,
    mdiListBox
} from "@mdi/js";
import SectionMain from "@/Components/SectionMain.vue";
import CardBoxWidget from "@/Components/CardBoxWidget.vue";
import LayoutAuthenticated from "@/Layouts/LayoutAuthenticated.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";

const props = defineProps({
    totalItems: {
        type: Object,
        default: () => ({}),
    },
    totalOrders: {
        type: Object,
        default: () => ({}),
    },
    totalUsers: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Dashboard" />
        <SectionMain>
            <SectionTitleLineWithButton
                :icon="mdiChartTimelineVariant"
                title="Dashboard"
                main
            >
            </SectionTitleLineWithButton>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 mb-6">
                <CardBoxWidget
                    color="text-emerald-500"
                    :icon="mdiAccountMultiple"
                    :number="props.totalUsers"
                    label="Total Users"
                />
                <CardBoxWidget
                    color="text-blue-500"
                    :icon="mdiInvoice"
                    :number="props.totalOrders"
                    label="Total Orders"
                />
                <CardBoxWidget
                    color="text-red-500"
                    :icon="mdiListBox"
                    :number="props.totalItems"
                    label="Total Items"
                />
            </div>
        </SectionMain>
    </LayoutAuthenticated>
</template>
