<script setup>
import { Link } from "@inertiajs/vue3";

    const props = defineProps({
        links: Array
    })
</script>
<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border dark:border-gray-600 rounded"
                    v-html="link.label" />
                <Link v-else
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-indigo-400 dark:hover:text-indigo-900"
                    :class="{ 'bg-blue-700 dark:bg-indigo-500 text-white dark:text-indigo-900': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>