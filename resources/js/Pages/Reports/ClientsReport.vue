<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from "@inertiajs/vue3";
import { onBeforeMount, ref } from 'vue';
import { usePermissions } from "@/Composables/permissions";
import VueMultiselect from "vue-multiselect";
import Table from '@/Components/Table.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableRow from '@/Components/TableRow.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Spinner from '@/Components/Spinner.vue';
import moment from 'moment/min/moment-with-locales';

moment.locale('ru')
    
const { hasRole } = usePermissions();
const showSpinner = ref(false);

const clients = ref([])
const records = ref([])
const client_id = ref()
const dateValue = ref()
const format = 'dd.MM.yyyy'
let filters = {}

async function getReport() {
    showSpinner.value = true;
    if (client_id.value.id > 0) {
        filters = { 'start_date':dateValue.value[0], 'end_date':dateValue.value[1], 'client_id': client_id.value.id }
         } else {
        filters = { 'start_date':dateValue.value[0], 'end_date':dateValue.value[1] }
        }
    await axios.post('/api/get_clients_report', filters)
    .then((response) => {
        showSpinner.value=false;
        console.log(response)
        records.value = response.data.records
    })
    .catch((e) => {
        console.log('true')
        console.log(e)
        showSpinner.value = false;
    })
}

async function getClients() {
    showSpinner.value = true;
    await axios.get('/api/get_clients_list')
    .then((response) => {
        showSpinner.value = false
        clients.value = response.data.clients
        client_id.value = response.data.clients[0]
    })
    .catch((e) => {
    console.log(e)
        showSpinner.value = false
    })
}

onBeforeMount(() => {
    const startDate = moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()-6)).format('YYYY-MM-DD');
    const endDate =  moment(new Date()).format('YYYY-MM-DD');
    dateValue.value = [startDate, endDate]
    getClients()
})
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
                    <div class="w-72">  
                        <VueMultiselect
                            v-model="client_id"
                            :options="clients"
                            :multiple="false"
                            :close-on-select="true"
                            :searchable="true"
                            placeholder="Выберите клиента"
                            label="name"
                            track-by="id"
                            :showLabels="false"
                            />
                    </div> 
                    <div class="w-80">
                        <DatePicker v-model="dateValue" locale="ru" range auto-apply :hide-navigation="['time']" :format="format" model-type="yyyy-MM-dd"/>
                    </div>
                    <div class="w-25">
                        <PrimaryButton @click="getReport">
                            Сформировать отчет
                        </PrimaryButton>
                    </div>
            </div>
            <div class="flex flex-col mt-8">
                <div class="overflow-x-auto rounded-lg">
                    <div class="align-middle inline-block min-w-full">
                        <div class="shadow overflow-hidden sm:rounded-lg">
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Направление</TableHeaderCell>
                                        <TableHeaderCell>Кол-во занятий за период</TableHeaderCell>
                                        <TableHeaderCell>Кол-во занятий по датам</TableHeaderCell>
                                        <TableHeaderCell>Израсходовано за период</TableHeaderCell>
                                        <TableHeaderCell>Остаток</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="record in records" :key="record.id">
                                        <TableDataCell>{{ record.class_name }}</TableDataCell>
                                        <TableDataCell>{{ record.sum_count }}</TableDataCell>
                                        <TableDataCell class="flex flex-wrap">
                                            <span v-for="item in record.records_by_date" :key="item.id"
                                            class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-500 dark:text-indigo-900 whitespace-nowrap mt-2"
                                            >
                                                {{ item.educationDate }}&nbsp;-&nbsp;<span class="font-semibold">{{ item.date_count }}</span>
                                            </span>
                                        </TableDataCell>
                                        <TableDataCell>{{ record.present }}</TableDataCell>
                                        <TableDataCell>{{ record.not_present }}</TableDataCell>
                                    </TableRow>
                                </template>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Spinner :showSpinner="showSpinner" />    
</AuthenticatedLayout>
</template>
<style scoped>
 @media (prefers-color-scheme: dark) {
    .multiselect__spinner {
        background: rgb(31 41 55 / var(--tw-bg-opacity));
      }
  }
</style>