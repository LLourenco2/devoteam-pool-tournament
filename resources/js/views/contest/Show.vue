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
        <div class="grid grid-cols-12 gap-8" v-if="contest">
            <div class="col-span-12 lg:col-span-10 card" style="margin: 0;">
                <div class="font-semibold text-xl mb-4">Tournament</div>
                <div>{{ contest.tournament.name }}
                    <Tag class="ml-2" :value="contest.tournament.status?.toUpperCase()"
                        :severity="getContestStatusColor(contest.tournament)" />
                </div>
            </div>
            <div class="col-span-12 lg:col-span-2" style="margin: 0;">
                <InfoCardTag title="Total Balls Left" :data="contest.loserBallsLeft" color="blue" icon="pi-circle" />
            </div>
            <div class="col-span-12 lg:col-span-4 card" style="margin: 0;">
                <PlayerCardTag title="Player 1" :avatarUrl="contest.player1.avatarUrl" :name="contest.player1.name" />
            </div>
            <div class="col-span-12 lg:col-span-4 card" style="margin: 0;">
                <PlayerCardTag title="Player 2" :avatarUrl="contest.player2.avatarUrl" :name="contest.player2.name" />
            </div>
            <div class="col-span-12 lg:col-span-4 p-8 rounded-lg shadow bg-green-100" style="margin: 0;">
                <PlayerCardTag title="Winner" :avatarUrl="contest.winner.avatarUrl" :name="contest.winner.name" />
            </div>
        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import { useRoute, useRouter } from 'vue-router'
import contestService from '@/services/Contest'
import { getContestStatusColor } from "../tournament/config";
import PlayerCardTag from '@/genericComponents/PlayerCardTag.vue';
import InfoCardTag from '@/genericComponents/InfoCardTag.vue';

const route = useRoute()
const toast = useToast()
const isLoading = ref(false)
const contest = ref(null)

const home = ref({
    label: 'Tournaments',
    route: '/'
});
const items = ref([
    {
        label: 'Matches',
    },
    {
        label: 'Match Details'
    },
]);

const getContest = async () => {
    try {
        isLoading.value = true;
        const response = await contestService.show(route.params.id);
        contest.value = response.data;
        items.value[0]['route'] = `/tournaments/${response.data.tournament.id}`;
        isLoading.value = false;
    } catch (error) {
        toast.add({ severity: 'error', summary: 'Unexpected Error', life: 3000 });
    }
}

onMounted(async () => {
    getContest()
});
</script>