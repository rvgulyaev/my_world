<template>
    <button class="bg-emerald-300 text-emerald-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-cyan-500 dark:text-cyan-900">
        <svg class="w-2.5 h-2.5 me-1.5 text-emerald-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
        </svg>
        <slot />
    </button>
</template>