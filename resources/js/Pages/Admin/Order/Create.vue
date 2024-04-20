<script setup>
import { ref, computed, reactive } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { mdiMenu, mdiArrowLeftBoldOutline, mdiTrashCan } from "@mdi/js";
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
    order_description: "",
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
        item_count: 1,
        item_total: 0,
    });
};

const totalPrice = computed(() => {
    return localItems.reduce((acc, item) => acc + item.item_total, 0);
});

const calculateItemPrice = (item, index) => {
    const selectedItemId = item.items;

    const isDuplicate = localItems.some(
        (existingItem, existingIndex) =>
            existingItem.items === selectedItemId && existingIndex !== index
    );

    if (isDuplicate) {
        alert("This item is already selected in another row. Please choose a different item.");
        item.items = ""
        return;
    }

    let selectedItem = props.itemOptions.find((i) => i.id == selectedItemId);

    if (selectedItem) {
        item.item_price = selectedItem.price;
        updateItemTotal(item);
    } else {
        item.items = "";
        console.error(`Item with ID ${selectedItemId} not found in options`);
    }
};
const updateItemTotal = (item) => {
    item.item_total = item.item_price * item.item_count;
};

const handleRemoveItem = (index) => {
    localItems.splice(index, 1);
};

const handleItemCountChange = (item) => {
  if (item.item_count < 1) {
    item.item_count = 1;
  }
};

const handleSubmit = () => {
    const itemsData = localItems.map((item) => ({
        id: item.items,
        price: item.item_price,
        count: item.item_count,
        total: item.item_total,
    }));
    form.items = itemsData;

    form.post(route("admin.orders.store"), { _token: "{{ csrf_token() }}" });
};
</script>

<template>
    <LayoutAuthenticated>
        <Head title="Create Order" />
        <SectionMain>
            <SectionTitleLineWithButton :icon="mdiMenu" title="Add Order" main>
                <BaseButton
                    :route-name="route('admin.orders.index')"
                    :icon="mdiArrowLeftBoldOutline"
                    label="Back"
                    color="white"
                    rounded-full
                    small
                />
            </SectionTitleLineWithButton>
            <CardBox form @submit.prevent="handleSubmit">
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
                            class="w-1/4"
                        >
                            <FormControl
                                v-model="i.items"
                                type="select2"
                                placeholder="Add Items"
                                :error="form.errors.items"
                                :options="itemOptions"
                                @change="calculateItemPrice(i, index)"
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
                            class="w-1/4"
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
                            class="w-1/4"
                        >
                            <FormControl
                                v-model="localItems[index].item_count"
                                type="number"
                                placeholder="1"
                                :error="form.errors.item_count"
                                @change="handleItemCountChange(i)"
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
                                v-model="localItems[index].item_total"
                                type="number"
                                :value="updateItemTotal(i)"
                            >
                                <div
                                    class="text-red-400 text-sm"
                                    v-if="form.errors.item_total"
                                >
                                    {{ form.errors.item_total }}
                                </div>
                            </FormControl>
                        </FormField>
                        <FormField
                            label="Operation"
                            class="text-red-400 w-1/10"
                        >
                            <BaseButton
                                color="danger"
                                :icon="mdiTrashCan"
                                small
                                class="mt-2"
                                @click="handleRemoveItem(index)"
                            />
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

                <FormField
                    label="Order Description"
                    :class="{ 'text-red-400': form.errors.order_description }"
                    class="mt-10"
                >
                    <FormControl
                        v-model="form.order_description"
                        type="textarea"
                        placeholder="Add description about order"
                        :error="form.errors.order_description"
                    >
                        <div
                            class="text-red-400 text-sm"
                            v-if="form.errors.order_description"
                        >
                            {{ form.errors.order_description }}
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
                        <div class="flex items-center">
                            <span>Total Price:</span>
                            <span class="ml-2">{{ totalPrice }}</span>
                        </div>
                    </BaseButtons>
                </template>
            </CardBox>
        </SectionMain>
    </LayoutAuthenticated>
</template>
