<template>
    <div class="card mb-0">
        <div class="flex justify-between mb-4">
            <div>
                <span class="block text-muted-color font-medium mb-4">{{ title }}</span>
                <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">{{ data
                }}</div>
            </div>
            <div :class="`flex items-center justify-center bg-${color}-100 dark:bg-${color}-400/10 rounded-border`"
                style="width: 2.5rem; height: 2.5rem">
                <i :class="`pi ${icon} text-${color}-500 !text-xl`"></i>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    title: {
        type: String,
        required: true
    },
    data: {
        type: [String, Number],
        required: true
    },
    color: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        required: true
    }
})
</script>