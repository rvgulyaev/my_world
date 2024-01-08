<script setup>
import { VueSpinner } from 'vue3-spinners';

const props = defineProps({
    showSpinner: false
})

</script>

<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div v-show="showSpinner" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50" scroll-region>
                <Transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="showSpinner" class="fixed inset-0 transform transition-all">
                        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" />
                    </div>
                </Transition>
                <div class="grid h-screen place-items-center">
                    <div>
                        <VueSpinner size="50" color="MediumSlateBlue"/>
                    </div>
                </div>                
            </div>
        </Transition>
    </Teleport>
</template>