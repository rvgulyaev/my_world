<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>
<template>
    <Link :href="href"
        class="block w-full bg-blue-100 text-blue-800 px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest dark:bg-indigo-500 dark:text-indigo-900 dark:hover:bg-indigo-400 hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
        <slot />
    </Link>
</template>