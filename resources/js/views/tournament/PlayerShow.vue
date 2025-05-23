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
        <div v-if="!stats" class="text-center">
            <ProgressSpinner />
        </div>
        <div class="grid grid-cols-12 gap-8" v-else>
            <div class="col-span-12 lg:col-span-6 card" style="margin: 0;">
                <PlayerCardTag title="Player" :avatarUrl="stats.player.avatarUrl" :name="stats.player.name" />
            </div>
            <div class="col-span-12 lg:col-span-6 card" style="margin: 0;">
                <div class="font-semibold text-xl mb-4">Tournament</div>
                <div class="py-6">{{ stats.tournament.name }}
                    <Tag class="ml-2" :value="stats.tournament.status.toUpperCase()"
                        :severity="getTournamentStatusColor(stats.tournament)" />
                </div>
            </div>
            <div class="col-span-12 lg:col-span-6 xl:col-span-3">
                <InfoCardTag title="Wins" :data="stats.stats?.wins" color="green" icon="pi-trophy" />
            </div>
            <div class="col-span-12 lg:col-span-6 xl:col-span-3">
                <InfoCardTag title="Losses" :data="stats.stats?.losses" color="red" icon="pi-arrow-down" />
            </div>
            <div class="col-span-12 lg:col-span-6 xl:col-span-3">
                <InfoCardTag title="Games Played" :data="stats.stats?.total" color="blue" icon="pi-box" />
            </div>
            <div class="col-span-12 lg:col-span-6 xl:col-span-3">
                <InfoCardTag title="Total Balls Left" :data="stats.stats?.totalBallsLeft" color="blue"
                    icon="pi-circle" />
            </div>
            <div class="col-span-12 card">
                <ContestsTable :contests="stats.contests" :isLoading="isLoading" @goToShow="goToShow" />
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import { useRoute, useRouter } from 'vue-router'
import tournamentService from '@/services/Tournament'
import InfoCardTag from '@/genericComponents/InfoCardTag.vue'
import ContestsTable from './components/ContestsTable.vue'
import { getTournamentStatusColor } from "./config";
import PlayerCardTag from '@/genericComponents/PlayerCardTag.vue';

const route = useRoute()
const router = useRouter()
const toast = useToast()
const isLoading = ref(false)
const stats = ref(null)

const home = ref({
    label: 'Tournaments',
    route: '/'
});
const items = ref([
    {
        label: 'Matches',
        route: `/tournaments/${route.params.tournamentId}`
    },
    {
        label: 'Player Details'
    },
]);

const goToShow = (data, type) => {
    switch (type) {
        case 'contest':
            router.push({ name: 'contests.show', params: { id: data.id } })
            return;
    }
};

const getStats = async () => {
    try {
        isLoading.value = true;
        const response = await tournamentService.showPlayer(route.params.tournamentId, route.params.playerId);
        stats.value = response.data;
        isLoading.value = false;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

onMounted(async () => {
    getStats()
});
</script>