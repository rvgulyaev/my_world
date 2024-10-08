<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from "@inertiajs/vue3";
import { onBeforeMount, ref } from 'vue';
import Table from '@/Components/Table.vue';
import Spinner from '@/Components/Spinner.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import moment from 'moment/min/moment-with-locales';
import { datepickeroptions } from '@/Composables/datepickeroptions';
import axios from 'axios';

moment.locale('ru')
const weekdata = ref();
const classes = ref();
const showSpinner = ref(false);
let filters = {};
let data = {};
const dateValue = ref();
const format = 'dd.MM.yyyy';
const startDate = moment().subtract(1, 'weeks').startOf('week').format('YYYY-MM-DD');
const endDate = moment().subtract(1, 'weeks').endOf('week').format('YYYY-MM-DD');

onBeforeMount(() => {
dateValue.value = [startDate, endDate];
getReport();
})
async function getReport() {
        showSpinner.value = true;
        filters = { 'start_date':dateValue.value[0], 'end_date':dateValue.value[1] }
        await axios.post('/api/get_week_report', filters)
        .then((response) => {
            showSpinner.value=false;
            weekdata.value = response.data.weekdata;
            classes.value = response.data.classes;
        })
        .catch((e) => {
            showSpinner.value = false;
    })
}
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
            <div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                    Недельный отчет {{ moment(startDate, "YYYY-MM-DD").format("DD.MM.YYYY") }} - {{ moment(endDate, "YYYY-MM-DD").format("DD.MM.YYYY") }}
                </h3>
            </div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <span class="text-base font-normal text-gray-500">
                        Отчет о песещении занятий за предыдущую неделю по всем направлениям. <br/>
                        (Примечание: Отчетный период - предыдущая календарная неделя)
                    </span>
                    <h3 class="mb-2 text-gray-900 dark:text-gray-300">Выберите период для отображения отчета:</h3>
                </div>
                <div class="flex items-center justify-between mb-4">
                        <a v-bind:href="route('reports.weekreport.export')" class="inline-flex items-center px-4 py-2 ml-4 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm whitespace-nowrap dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                            В Excel
                        </a>
                </div>
            </div>
            <div class="flex items-center mb-4 space-x-2">
                <div class="w-80">
                    <DatePicker v-model="dateValue" locale="ru" range auto-apply :hide-navigation="['time']" :format="format" model-type="yyyy-MM-dd"/>
                </div>
                <div class="w-25">
                    <PrimaryButton @click="getReport" class="mb-0">Сформировать отчет</PrimaryButton>
                </div>
            </div> 
            <div class="overflow-auto h-[70%]" v-if="typeof weekdata !== 'undefined'">
                <table class="min-w-full text-sm font-light text-start text-surface dark:text-white">
                    <thead class="font-medium border-b border-neutral-200 dark:border-white/10">
                        <tr class="bg-gray-100 border border-gray-200">
                            <th scope="col" class="px-6 py-4 border border-gray-200">Id</th>
                            <th scope="col" class="px-6 py-4 border border-gray-200">ФИО</th>
                            <th scope="col" class="px-6 py-4 border border-gray-200" v-for="classitem in classes" :key="classitem.class_id">{{ classitem.class_name }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="client in weekdata" :key="client.id">
                            <td class="px-2 py-1 text-center border border-gray-100">{{ client.id }}</td>
                            <td class="px-2 py-1 border border-gray-100 whitespace-nowrap">{{ client.fio }}</td>
                            <template v-for="(client_data, index) in client.clientdata" :key=index>
                                <td class="px-2 py-1 text-center border border-gray-100">{{ client_data }}</td>
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
