<script setup>
import { ref, computed, reactive } from "vue";
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

const form = useForm({
    customer_name: "",
    customer_address: "",
    items: "",
    item_price: "",
    item_count: "",
    item_total: "",
});
const props = defineProps({
    itemOptions: {
        type: Object,
        default: () => ({}),
    },
});

const localItems = reactive([
    {
        items: "",
        item_price: 0,
        item_count: 1,
        item_total: 0,
    },
]);

const addItem = () => {
    localItems.push({
        items: "",
        item_price: 0,
        item_count: 0,
        item_total: 0,
    });
};

const calculateItemPrice = (item) => {
    let selectedItem = props.itemOptions.find((i) => i.id == item.items);

    item.item_price = selectedItem.price;
    item.item_total = item.item_price * item.item_count;
};

const updateItemTotal = (item) => {
    item.item_total = item.item_price * item.item_count;
};

const getItemTotal = computed(() => {
    if (!Array.isArray(localItems.value) || localItems.value.length === 0) {
        return 0; // Return default value or handle the case when localItems is not an array or is empty
    }

    return localItems.value.reduce((total, item) => {
        return total + (item.item_price * item.item_count);
    }, 0);
});
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Create menu" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiMenu" title="Add menu" main>
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
                        <div
                            class="text-red-400 text-sm"
                            v-if="form.errors.customer_name"
                        >
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
                        <div
                            class="text-red-400 text-sm"
                            v-if="form.errors.customer_address"
                        >
                            {{ form.errors.customer_address }}
                        </div>
                    </FormControl>
                </FormField>
                <div v-for="(i, index) in localItems" :key="index">
                    <div class="flex space-x-5">
                        <FormField
                            label="Select Item"
                            :class="{ 'text-red-400': form.errors.items }"
                        >
                            <FormControl
                                v-model="i.items"
                                type="select2"
                                placeholder="Add Items"
                                :error="form.errors.items"
                                :options="itemOptions"
                                @change="calculateItemPrice(i)"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="form.errors.items"
                                >
                                    {{ form.errors.items }}
                                </div>
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Item Price"
                            :class="{ 'text-red-400': form.errors.item_price }"
                        >
                            <FormControl
                                v-model="i.item_price"
                                type="number"
                                placeholder="0"
                                :error="form.errors.item_price"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="form.errors.item_price"
                                >
                                    {{ form.errors.item_price }}
                                </div>
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Item Count"
                            :class="{ 'text-red-400': form.errors.item_count }"
                        >
                            <FormControl
                                v-model="localItems[index].item_count"
                                type="number"
                                placeholder="1"
                                :error="form.errors.item_count"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="form.errors.item_count"
                                >
                                    {{ form.errors.item_count }}
                                </div>
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Item Total"
                            :class="{ 'text-red-400': form.errors.item_total }"
                        >
                            <FormControl
                                v-model="getItemTotal"
                                type="number"
                                :value="calculateItemTotal"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="form.errors.item_total"
                                >
                                    {{ form.errors.item_total }}
                                </div>
                            </FormControl>
                        </FormField>
                    </div>
                </div>
                <BaseButtons>
                    <BaseButton
                        type="button"
                        color="success"
                        label="Add Item"
                        :class="{ 'opacity-25': form.processing }"
                        @click="addItem"
                    />
                </BaseButtons>
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
