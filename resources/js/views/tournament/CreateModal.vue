<template>
    <Dialog visible modal :style="{ width: '450px' }" header="Tournament Details" @update:visible="closeModal">
        <Form v-slot="$form" :tournament :resolver @submit="save">
            <div class="flex flex-col gap-6">
                <InputTextTag v-model="$form.name" name="name" label="Name" :serverErrors="serverErrors" required />
                <InputNumberTag v-model="$form.numberPlayers" name="numberPlayers" label="Number Of Players"
                    :serverErrors="serverErrors" required />
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <Button label="Cancel" icon="pi pi-times" text @click="closeModal" :disabled="isLoading" />
                <Button label="Save" type="submit" icon="pi pi-check" :loading="isLoading"
                    :disabled="!somethingChanged" />
            </div>
        </Form>
    </Dialog>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useToast } from 'primevue/usetoast';
import tournamentService from '@/services/Tournament'
import InputTextTag from '@/genericComponents/InputTextTag.vue';
import InputNumberTag from '@/genericComponents/InputNumberTag.vue';

const emit = defineEmits(['closeModal', 'getData'])
const toast = useToast();
const serverErrors = ref({});
const somethingChanged = ref(false);
const isLoading = ref(false);
const tournament = reactive({
    name: null,
    numberPlayers: null
});


const resolver = ({ values }) => {
    const errors = {};
    somethingChanged.value = true;
    if (!values.name) {
        errors.name = [{ message: 'Name is required.' }];
    }
    if (!values.numberPlayers) {
        errors.numberPlayers = [{ message: 'Number Of Players is required.' }];
    }
    return {
        values,
        errors
    };
};

function closeModal() {
    emit('closeModal');
}

async function save({ valid, values }) {
    if (valid) {
        try {
            isLoading.value = true;
            await tournamentService.store(values);
            toast.add({ severity: 'success', summary: 'Tournament Created', life: 3000 });
            emit('getData');
            closeModal();
        } catch (error) {
            isLoading.value = false;
            if (error.status === 422) {
                serverErrors.value = error.response.data.errors
            } else {
                toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
            }

        }
    }
}
</script>