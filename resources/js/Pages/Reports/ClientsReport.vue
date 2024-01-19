<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from 'vue';
import { usePermissions } from "@/Composables/permissions";
import VueMultiselect from "vue-multiselect";
import TextInput from "@/Components/TextInput.vue";
import { datepickeroptions } from '@/Composables/datepickeroptions'
    
    
const { hasRole } = usePermissions();
const { options } = datepickeroptions();

const props = defineProps({
    clients: Array
})
const form = useForm({
    search_client: null
})
const checkedClient = ref(null)
const dateValue = ref({
  startDate: "",
  endDate: "",
});
const formatter = ref(options.formatter)
const customShortcuts = () => {
  return options.customShortcuts
};

function dDate(date) {
  return date > new Date()
}
</script>

<template>
     <Head title="Учет посещений" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 leading-tight">
            Учет посещений
        </h2>
    </template>

    <div class="mb-4">
        <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 h-screen">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                        Учет посещений
                    </h3>
                    <span class="text-base font-normal text-gray-500"
                        >Отчет о песещении занятий за определенный период - по умолчанию период за последнюю(не календарную) неделю.</span
                    >
                </div>
            </div>
            <div>
                <h3 class="text-gray-900 dark:text-gray-300 mb-2">
                    Выберите клиента и период для отображения отчета
                </h3>
            </div>
            <div class="mb-4 flex items-center space-x-2">
                    <div>  
                        <VueMultiselect
                            v-model="checkedClient"
                            :options="clients"
                            :multiple="false"
                            :close-on-select="true"
                            :searchable="true"
                            placeholder="Выберите клиента"
                            label="name"
                            track-by="id"
                            class="w-72"
                            :showLabels="false"
                            />
                    </div>
                    <div>                        
                        <vue-tailwind-datepicker v-model="dateValue" i18n="ru" placeholder="Выберите отчетный период" :shortcuts="customShortcuts" :formatter="formatter" :disable-date="dDate" as-single use-range/>
                    </div>  
            </div>
            <div class="flex flex-col mt-8">
                <div class="overflow-x-auto rounded-lg">
                    <div class="align-middle inline-block min-w-full">
                        <div class="shadow overflow-hidden sm:rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>
<style scoped>
 @media (prefers-color-scheme: dark) {
    .multiselect__spinner {
        background: rgb(31 41 55 / var(--tw-bg-opacity));
      }
  }
</style>