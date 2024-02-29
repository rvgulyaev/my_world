<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    search: {
        type: String,
        required: true,
    },
    route_link: {
        type: String,
        required: true
    },
    search_field: {
        type: String,
        required: true
    },
    pholder: {
        type: String,
        default: "Поиск..."
    }
})

const searchForm = useForm({
    search: props.search
})

const clearSearchForm = () => {
    searchForm.search = ''
    searchBy()
}

const searchBy = () => {
    let arg = Object.fromEntries(new Map([ [props.search_field, searchForm.search] ]))
    searchForm.get(route(props.route_link, arg))
}

</script>
<template>
    <div class="relative text-gray-700">
        <input class="md:w-60 lg:w-80 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
        type="text" 
        :placeholder="props.pholder"
        v-model="searchForm.search"                       
        autocomplete="search"
        v-on:keyup.enter="searchBy"
        />
        <button class="absolute inset-y-0 right-0 flex items-center px-4 font-bold text-blue-800 bg-blue-100 rounded-r-lg hover:bg-indigo-500 focus:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-300"
        v-on:click="clearSearchForm"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</template>