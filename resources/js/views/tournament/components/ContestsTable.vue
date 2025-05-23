<template>
    <DataTable :value="contests" :loading="isLoading" stripedRows scrollable scrollHeight="60vh"
        tableStyle="min-width: 50rem">
        <template #header>
            <div class="flex flex-wrap gap-2 items-center justify-between">
                <h4 class="m-0">Matches</h4>
                <slot name="header" />
            </div>
        </template>
        <Column header="Player 1" style="width: 20%; height: 44px">
            <template #body="{ data }">
                <div class="flex items-center gap-2">
                    <img :alt="data.player1.name" :src="data.player1.avatarUrl" style="width: 32px" />
                    <span>{{ data.player1.name }}</span>
                </div>
            </template>
        </Column>
        <Column header="Player 2" style="width: 20%; height: 44px">
            <template #body="{ data }">
                <div class="flex items-center gap-2">
                    <img :alt="data.player2.name" :src="data.player2.avatarUrl" style="width: 32px" />
                    <span>{{ data.player2.name }}</span>
                </div>
            </template>
        </Column>
        <Column header="Winner" style="width: 20%; height: 44px">
            <template #body="{ data }">
                <div v-if="data.winner" class="flex items-center gap-2">
                    <img :alt="data.winner.name" :src="data.winner.avatarUrl" style="width: 32px" />
                    <span>{{ data.winner.name }}</span>
                </div>
            </template>
        </Column>
        <Column field="loserBallsLeft" header="Loser Balls Left" style="width: 20%; height: 44px" />
        <Column header="Status">
            <template #body="slotProps">
                <Tag :value="slotProps.data.status.toUpperCase()" :severity="getContestStatusColor(slotProps.data)" />
            </template>
        </Column>
        <Column class="w-24 !text-end">
            <template #body="{ data }">
                <Button icon="pi pi-search" @click="emit('goToShow', data, 'contest')" severity="secondary" rounded
                    :disabled="data.status != 'ended'"></Button>
            </template>
        </Column>
        <template #empty>
            <div v-if="!isLoading" class="text-center"> No matches found. </div>
        </template>
    </DataTable>
</template>

<script setup>
import { getContestStatusColor } from "../config";

const emit = defineEmits(['goToShow'])
const props = defineProps({
    contests: {
        type: Array,
        required: true
    },
    isLoading: {
        type: Boolean,
        required: true
    }
})
</script>