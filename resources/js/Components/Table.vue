<script setup>
</script>

<template>
  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-500">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <slot name="header"></slot>
        </thead>
        <tbody class="bg-white dark:bg-gray-600">
            <slot></slot>
        </tbody>
    </table>
</template>
