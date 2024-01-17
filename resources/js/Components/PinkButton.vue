<template>
    <button
    class="bg-pink-300 text-pink-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-0.5 dark:bg-pink-500 dark:text-pink-900"
    >
    <svg class="w-2.5 h-2.5 me-1.5 text-pink-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                                                    </svg>
        <slot />
    </button>
</template>
