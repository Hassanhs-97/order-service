<script setup>
import { reactive } from "vue";
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
    order: {
        type: Object,
        default: () => ({}),
    },
    orderItems: {
        type: Object,
        default: () => ({}),
    }
});

const localItems = reactive(props.orderItems || []);



</script>

<template>
    <LayoutAuthenticated>
        <Head title="Show Order" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiMenu" title="Show Order" main>
                <BaseButton
                    :route-name="route('admin.orders.index')"
                    :icon="mdiArrowLeftBoldOutline"
                    label="Back"
                    color="white"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>
            <CardBox  @submit.prevent="">
                <FormField
                    label="Customer Name"
                >
                    <FormControl
                        v-model="props.order.customer_name"
                        type="text"
                        placeholder="Enter Customer Name"
                    >
                    </FormControl>
                </FormField>
                <FormField
                    label="Customer Address"
                >
                    <FormControl
                        v-model="props.order.customer_address"
                        type="text"
                        placeholder="Enter Customer Address"
                    >
                    </FormControl>
                </FormField>
                <div v-for="(i, index) in localItems" :key="index">
                    <div class="flex space-x-5">
                        <FormField
                            label="Selected Item"
                            class="w-1/4"
                        >
                            <FormControl
                                v-model="localItems[index].name"
                                type="input"
                                placeholder="Add Items"
                            >
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Item Price"
                            class="w-1/4"
                        >
                            <FormControl
                                v-model="localItems[index].item_price"
                                type="number"
                                placeholder="0"
                            >
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Item Count"
                            class="w-1/4"
                        >
                            <FormControl
                                v-model="localItems[index].item_count"
                                type="number"
                                placeholder="1"
                            >
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Item Total"
                        >
                            <FormControl
                                v-model="localItems[index].item_total"
                                type="number"
                                :value="localItems[index].item_total"
                            >
                            </FormControl>
                        </FormField>
                    </div>
                </div>

                <FormField
                    label="Order Description"
                    class="mt-10"
                >
                    <FormControl
                        v-model="props.order.order_description"
                        type="textarea"
                    >
                    </FormControl>
                </FormField>
                <template #footer>
                    <div class="flex items-center">
                        <span>Total Price:</span>
                        <span class="ml-2">{{ props.order.total_price }}</span>
                    </div>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
