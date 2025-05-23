<template>
    <div class="font-semibold text-xl mb-4">{{ title }}</div>
    <div class="flex">
        <Image :src="avatarUrl" alt="Image" width="60" class="mr-5" />
        <div class="py-6">{{ name }}</div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true
    },
    avatarUrl: {
        type: String,
        required: true
    },
    name: {
        type: String,
        required: true
    },
})
</script>