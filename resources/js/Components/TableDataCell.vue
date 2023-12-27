<script setup>
import { computed } from 'vue';
const props =defineProps({
    classes: {
        type: String,
        required: false
    }
})
const clas = computed(() =>
    props.classes == '' ?
    "p-4 whitespace-nowrap text-sm font-normal text-gray-900 dark:text-gray-300 border-b dark:border-gray-500 " + props.classes :
    "p-4 whitespace-nowrap text-sm font-normal text-gray-900 dark:text-gray-300 border-b dark:border-gray-500 "
);
</script>
<template>
    <td :class="clas">
        <slot />
    </td>
</template>
