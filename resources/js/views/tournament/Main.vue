<template>
    <div class="card">
        <Filter v-if="openModal == 'filter'" v-model="filters" @getData="getTournaments" @closeModal="closeModal" />
        <DataTable lazy paginator stripedRows scrollable scrollHeight="65vh" :value="tournaments" striped-rows
            :total-records="totalRecords" :loading="isLoading" :rows="perPage" :rowsPerPageOptions="[5, 10, 20, 50]"
            @page="onPage($event)" @update:rows="onRow($event)"
            paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
            currentPageReportTemplate="{first} to {last} of {totalRecords}">
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0">Tournaments</h4>
                    <div class="flex gap-2">
                        <Button :icon="`pi pi-filter${Object.keys(filters).length !== 0 ? '-fill' : ''}`"
                            aria-label="Fitler" @click="showFilter" />
                        <Button label="New" icon="pi pi-plus" severity="secondary" @click="openNew" />
                    </div>
                </div>
            </template>
            <Column field="name" header="Name" />
            <Column header="Status">
                <template #body="slotProps">
                    <Tag :value="slotProps.data.status.toUpperCase()"
                        :severity="getTournamentStatusColor(slotProps.data)" />
                </template>
            </Column>
            <Column class="w-24 !text-end">
                <template #body="{ data }">
                    <Button icon="pi pi-search" @click="goToTournament(data.id)" severity="secondary" rounded></Button>
                </template>
            </Column>
            <template #empty>
                <div v-if="!isLoading" class="text-center"> No tournaments found. </div>
            </template>
        </DataTable>
        <CreationModal v-if="openModal == 'create'" @getData="getTournaments" @closeModal="closeModal" />
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router'
import tournamentService from '@/services/Tournament'
import CreationModal from './CreateModal.vue';
import Filter from './Filter.vue';
import { getTournamentStatusColor } from "./config";

const toast = useToast();
const router = useRouter()
const tournaments = ref([]);
const isLoading = ref(false);
const openModal = ref();
const page = ref(1);
const perPage = ref(10);
const totalRecords = ref(0);
const filters = ref({});

const onPage = (event) => {
    page.value = event.page + 1
    getTournaments()
}

const onRow = (event) => {
    perPage.value = event
}

const showFilter = () => {
    openModal.value = 'filter'
}

const openNew = () => {
    openModal.value = 'create'
}

const closeModal = () => {
    openModal.value = null
}

const goToTournament = (id) => {
    router.push({ name: 'tournaments.show', params: { id } })
}

const getTournaments = async () => {
    try {
        isLoading.value = true;
        const response = await tournamentService.index({ page: page.value, perPage: perPage.value, filters: JSON.stringify(filters.value) });
        tournaments.value = response.data.data;
        totalRecords.value = response.data.totalRecords;
        isLoading.value = false;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

onMounted(async () => {
    getTournaments()
});
</script>