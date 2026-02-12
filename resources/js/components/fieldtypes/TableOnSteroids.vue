<script setup>
import { ref, computed } from "vue";
import { Fieldtype } from "@statamic/cms";
import { Button, Checkbox, Input, Label, Textarea } from "@statamic/cms/ui";
import Papa from "papaparse";

const emit = defineEmits(Fieldtype.emits);
const props = defineProps(Fieldtype.props);
const { expose, update } = Fieldtype.use(emit, props);
defineExpose(expose);

const csv = ref("");
const error = ref("");
const customDelimiter = ref("");
const useTab = ref(false);

const delimiter = computed(() => {
    if (useTab.value) return "\t";
    if (customDelimiter.value === "") return ",";
    return customDelimiter.value;
});

function onTableUpdate(newValue) {
    update(newValue);
}

function handleClearTable() {
    update([]);
}

function handleClearInput() {
    csv.value = "";
}

function csvToJavascript() {
    error.value = "";
    try {
        const { data, errors } = Papa.parse(csv.value, {
            header: true,
            skipEmptyLines: true,
            delimiter: delimiter.value === "," ? undefined : delimiter.value,
        });

        if (errors.length > 0) {
            error.value = errors[0].message;
            return;
        }

        if (!data.length) {
            error.value = "No data found in CSV";
            return;
        }

        const result = [];

        // First row: column headers
        const headerLine = { cells: Object.keys(data[0]) };
        result.push(headerLine);

        // Remaining rows: data
        data.forEach((entry) => {
            const line = { cells: Object.values(entry) };
            result.push(line);
        });

        update(result);
    } catch (e) {
        error.value = e.message;
    }
}
</script>

<template>
    <div>
        <table-fieldtype
            :value="value"
            :handle="handle"
            :config="config"
            :meta="meta"
            :read-only="readOnly"
            @update:value="onTableUpdate"
        />

        <Label text="Paste your CSV into this field:" class="mt-4" for="csv" />

        <Textarea v-model="csv" rows="4" id="csv" />

        <div class="flex items-center gap-2 mt-4">
            <Label text="Delimiter Settings:" for="delimiter" />
            <Input
                v-model="customDelimiter"
                :disabled="useTab"
                placeholder=","
                size="sm"
                class="grow-0"
                style="width: 40px !important"
                id="delimiter"
            />
            <Label text="OR" for="use-tab" />
            <Checkbox
                v-model="useTab"
                label="Use tab"
                id="use-tab"
                class="whitespace-nowrap"
            />
        </div>

        <div class="flex flex-wrap items-center gap-2 mt-4">
            <Button
                @click="csvToJavascript"
                variant="primary"
                text="💉 Parse CSV Data"
                size="sm"
            />
            <Button @click="handleClearInput" text="Clear Input" size="sm" />
            <Button @click="handleClearTable" text="Clear Table" size="sm" />
        </div>

        <p class="text-red-500 mt-2 text-sm" v-if="error">{{ error }}</p>
    </div>
</template>
