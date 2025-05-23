<template>
    <div>
        <div class="card" style="padding: 0.5rem; margin-bottom: 1rem;">
            <Breadcrumb :home="home" :model="items">
                <template #item="{ item, props }">
                    <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                        <a :href="href" v-bind="props.action" @click="navigate">
                            <span :class="[item.icon, 'text-color']" />
                            <span class="text-primary font-semibold">{{ item.label }}</span>
                        </a>
                    </router-link>
                    <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                        <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
                    </a>
                </template>
            </Breadcrumb>
        </div>
        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 card" v-if="tournament" style="margin: 0;">
                <div class="font-semibold text-xl mb-4">Tournament</div>
                <div>{{ tournament.name }}
                    <Tag class="ml-2" :value="tournament.status?.toUpperCase()"
                        :severity="getTournamentStatusColor(tournament)" />
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6">
                <div class="card">
                    <ContestsTable :contests="contests" :isLoading="isLoadingContests" @goToShow="goToShow">
                        <template #header>
                            <div class="flex gap-2">
                                <Button label="Simulate" icon="pi pi-box" severity="success" :loading="isSimulating"
                                    :disabled="isSimulating || tournament?.['status'] == 'ended'"
                                    @click="simpleSimulation" />
                            </div>
                        </template>
                    </ContestsTable>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 ">
                <div class="card">
                    <DataTable :value="leaderboard" :loading="isLoadingLeaderboard" stripedRows scrollable
                        scrollHeight="60vh" tableStyle="min-width: 50rem">
                        <template #header>
                            <div class="flex flex-wrap gap-2 items-center justify-between">
                                <h4 class="m-0">Leaderboard</h4>
                            </div>
                        </template>
                        <Column field="position" header="Position" style="width: 20%; height: 44px" />
                        <Column header="Player" style="width: 20%; height: 44px">
                            <template #body="{ data }">
                                <div class="flex items-center gap-2">
                                    <img :alt="data.player.name" :src="data.player?.avatarUrl" style="width: 32px" />
                                    <span>{{ data.player.name }}</span>
                                </div>
                            </template>
                        </Column>
                        <Column field="score" header="Score" style="width: 20%; height: 44px" />
                        <Column field="gamesWon" header="Games Won" style="width: 20%; height: 44px" />
                        <Column field="gamesLost" header="Games Lost" style="width: 20%; height: 44px" />
                        <Column class="w-24 !text-end">
                            <template #body="{ data }">
                                <Button icon="pi pi-search" @click="goToShow(data, 'player')" severity="secondary"
                                    rounded></Button>
                            </template>
                        </Column>
                        <template #empty>
                            <div v-if="!isLoadingLeaderboard" class="text-center"> No players found. </div>
                        </template>
                    </DataTable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import { useRoute, useRouter } from 'vue-router'
import { getTournamentStatusColor } from "./config";
import tournamentService from '@/services/Tournament'
import contestService from '@/services/Contest'
import echo from '@/bootstrap'
import ContestsTable from './components/ContestsTable.vue'

const router = useRouter()
const route = useRoute()
const toast = useToast()
const tournament = ref(null)
const isLoading = ref(false)
const isLoadingContests = ref(false)
const isLoadingLeaderboard = ref(false)
const isSimulating = ref(false)
const contests = ref([])
const leaderboard = ref([])

const home = ref({
    label: 'Tournaments',
    route: '/'
});
const items = ref([
    { label: 'Matches' }
]);

const goToShow = (data, type) => {
    switch (type) {
        case 'contest':
            router.push({ name: 'contests.show', params: { id: data.id } })
            return;

        case 'player':
            router.push({ name: 'tournaments.playerShow', params: { playerId: data.player.id, tournamentId: route.params.id } })
            return;
    }
};


const getTournament = async () => {
    try {
        isLoading.value = true;
        const response = await tournamentService.show(route.params.id);
        tournament.value = response.data;
        isSimulating.value = response.data.status == 'ongoing'
        isLoading.value = false;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

const getContests = async () => {
    try {
        isLoadingContests.value = true;
        const filters = JSON.stringify({ 'tournament': route.params.id });
        const response = await contestService.index({ filters });
        contests.value = response.data;
        isLoadingContests.value = false;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

const getLeaderboard = async () => {
    try {
        isLoadingLeaderboard.value = true;
        const response = await tournamentService.leaderboard(route.params.id);
        leaderboard.value = response.data;
        isLoadingLeaderboard.value = false;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

const simpleSimulation = async () => {
    try {
        isSimulating.value = true;
        await tournamentService.simpleSimulation(route.params.id);
        tournament.value['status'] = 'ongoing';
        toast.add({ severity: 'success', summary: 'Simulation Started', life: 3000 });
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

const startListens = () => {
    listenContestUpdated()
    listenLeaderboardUpdated()
    listenTournamentUpdated()
}

const listenContestUpdated = () => {
    echo.channel('tournament.' + route.params.id)
        .listen('ContestUpdated', (data) => {
            const index = contests.value.findIndex(p => p.id === data.id);
            if (index !== -1) {
                contests.value[index] = data;
            }
        });
}

const listenLeaderboardUpdated = () => {
    echo.channel('tournament.' + route.params.id)
        .listen('LeaderboardUpdated', (data) => {
            leaderboard.value = data;
        });
}

const listenTournamentUpdated = () => {
    echo.channel('tournament.' + route.params.id)
        .listen('TournamentUpdated', (data) => {
            tournament.value = data;
            isSimulating.value = false;
        });
}

onMounted(async () => {
    getTournament()
    getContests()
    getLeaderboard()
    startListens()
});
</script>