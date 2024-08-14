<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-600 animated-bg">
        <div>
            <Link href="/">
                <div class="logo-bg" />
            </Link>
        </div>

        <div
            class="z-10 w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-md dark:bg-gray-700 sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
<style scoped>
    .animated-bg {
    animation: theme 21s linear infinite;

    &:after,
    &:before {
        content: '';
        display: block;
        position: fixed;
        z-index: 0;
        top: 0;
        width: 100vw;
        height: 100vh;
        width: 100vmax;
        height: 100vmax;
        background: rgba(0,0,0,0.05);
        animation: background 90s linear infinite;
    }

    &:after {
        left: 15vw;
    }

    &:before {
        right: 15vw;
        animation-delay: -30s;
        animation-direction: reverse;
    }
}

@keyframes theme {
    0% {
        background: #f0fff5;
    }

    16% {
        background: #d9fffc;
    }

    33% {
        background: #e0fcff;
    }

    50% {
        background: #d9f2fd;
    }

    66% {
        background: #ffe3e3;
    }

    83% {
        background: #fefef1;
    }

    100% {
        background: #e5ffee;
    }
}
@media (prefers-color-scheme: dark) {
    @keyframes theme {
    0% {
        background: #4c566a;
    }

    16% {
        background: #574c6a;
    }

    33% {
        background: #4c6a61;
    }

    50% {
        background: #4c6a51;
    }

    66% {
        background: #626a4c;
    }

    83% {
        background: #6a4c5f;
    }

    100% {
        background: #6a694c;
    }
}
}

@keyframes background {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>
