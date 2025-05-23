<template>
    <div>
        <label :for="name" class="block font-bold mb-3">{{ label }} {{ required ? '*' : '' }}</label>
        <InputNumber :name="name" :placeholder="label" :invalid="model?.invalid" showButtons integeronly fluid />
        <Message v-if="model?.invalid || serverErrors[name]" severity="error" size="small" variant="simple">{{
            model.error?.message || serverErrors[name][0] }}</Message>
    </div>
</template>
<script setup>
const model = defineModel()
const props = defineProps({
    name: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    required: {
        type: Boolean,
        default: false
    },
    serverErrors: {
        type: Object,
        default: null
    }
})
</script>