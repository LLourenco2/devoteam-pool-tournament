<template>
    <Dialog visible modal :style="{ width: '450px' }" header="Filters" @update:visible="closeModal">
        <div class="flex flex-col mb-8">
            <label class="block font-bold mb-3">Status</label>
            <Select v-model="localModel.status" :options="statusOptions" optionValue="id" optionLabel="label"
                placeholder="Select a Status" class="w-full" />
        </div>
        <div class="flex justify-end gap-2">
            <Button type="button" label="Clear" severity="secondary" @click="clear" />
            <Button type="button" label="Filter" @click="applyFilter" />
        </div>
    </Dialog>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const emit = defineEmits(['closeModal', 'getData'])
const model = defineModel()
const localModel = ref({});

const statusOptions = ref([
    { id: 'open', label: 'Open' },
    { id: 'ongoing', label: 'Ongoing' },
    { id: 'ended', label: 'Ended' }
]);

function applyFilter() {
    model.value = {
        ...localModel.value,
    };
    emit('getData');
    closeModal();
}

function clear() {
    localModel.value = {};
}

function closeModal() {
    emit('closeModal');
}

onMounted(() => {
    localModel.value = { ...model.value };
});
</script>