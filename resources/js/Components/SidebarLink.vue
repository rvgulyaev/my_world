<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'text-base text-blue-900 dark:text-blue-200 font-normal rounded-md bg-blue-100 dark:bg-gray-800 flex items-center p-2 group '
        : 'text-base text-gray-900 dark:text-blue-200 font-normal rounded-md hover:bg-blue-100 dark:hover:bg-gray-800 hover:text-blue-900 dark:hover:text-blue-200 flex items-center p-2 group '
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
