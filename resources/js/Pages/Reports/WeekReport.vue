<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from "@inertiajs/vue3";
import { onBeforeMount, ref } from 'vue';
import { usePermissions } from "@/Composables/permissions";
import Table from '@/Components/Table.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableRow from '@/Components/TableRow.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Spinner from '@/Components/Spinner.vue';
import moment from 'moment/min/moment-with-locales';

moment.locale('ru')

const { hasRole } = usePermissions();
const props = defineProps({
    classes: {
        type: Object,
        required:true
    },
    weekdata: {
        type: Object,
        required: true
    }
})
const showSpinner = ref(false);

const dateValue = ref();
const startDate = ref();
const endDate = ref();

onBeforeMount(() => {
    startDate.value = moment().subtract(1, 'weeks').startOf('isoWeek').format('YYYY-MM-DD');
    endDate.value =  moment().subtract(1, 'weeks').endOf('isoWeek').format('YYYY-MM-DD');
    dateValue.value = [startDate.value, endDate.value];
})
</script>

<template>
<Head title="Недельный отчет" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="leading-tight text-gray-800">
            Недельный отчет
        </h2>
    </template>

    <div class="mb-4">
        <div class="h-screen p-4 bg-white rounded-lg shadow dark:bg-gray-700 sm:p-6 xl:p-8 w-[86%]">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                        Недельный отчет {{ moment(new Date(startDate)).format('DD.MM.YYYY') }} - {{ moment(new Date(endDate)).format('DD.MM.YYYY') }}
                    </h3>
                    <span class="text-base font-normal text-gray-500"
                        >Отчет о песещении занятий за предыдущую неделю по всем направлениям. <br/>
                        (Примечание: Отчетный период - предыдущая календарная неделя.)</span
                    >
                </div>
                <div class="flex items-center justify-between mb-4">
                        <a v-bind:href="route('reports.weekreport.export')" class="inline-flex items-center px-4 py-2 ml-4 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm whitespace-nowrap dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                            В Excel
                        </a>
                </div>
            </div> 
            <div class="overflow-auto h-[80%]" v-if="weekdata !== null">
                <table class="min-w-full text-sm font-light text-start text-surface dark:text-white">
                    <thead class="font-medium border-b border-neutral-200 dark:border-white/10">
                        <tr class="bg-gray-100 border border-gray-200">
                            <th scope="col" class="px-6 py-4 border border-gray-200">Id</th>
                            <th scope="col" class="px-6 py-4 border border-gray-200">ФИО</th>
                            <th scope="col" class="px-6 py-4 border border-gray-200" v-for="classitem in classes" :key="classitem.id">{{ classitem.class_name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in weekdata" :key="client.id">
                            <td class="px-2 py-1 text-center border border-gray-100">{{ client.id }}</td>
                            <td class="px-2 py-1 border border-gray-100 whitespace-nowrap">{{ client.fio }}</td>
                            <template v-for="clientdata in client.clientdata" :key="clientdata">
                                <td class="px-2 py-1 text-center border border-gray-100">{{ clientdata }}</td>
                            </template>                             
                        </tr>
                    </tbody>
                </table>
            </div>   
            <div class="p-4 border-l-8 border-red-300" v-else>Данные на указанный период и клиента отсутствуют в расписании. Попробуйте изменить клиента или период.</div>
        </div>
    </div>
    <Spinner :showSpinner="showSpinner" />
</AuthenticatedLayout>
</template>
