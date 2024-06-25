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
const client = ref(null)
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
        records.value = response.data.records
        client.value = response.data.client[0]
    })
    .catch((e) => {
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
        <h2 class="leading-tight text-gray-800">
            Учет посещений
        </h2>
    </template>

    <div class="mb-4">
        <div class="h-screen p-4 bg-white rounded-lg shadow dark:bg-gray-700 sm:p-6 xl:p-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                        Учет посещений
                    </h3>
                    <span class="text-base font-normal text-gray-500"
                        >Отчет о песещении занятий за определенный период - по умолчанию период за последнюю(не календарную) неделю. <br/>
                        (Примечание: Значение полей таблицы `Израсходовано за период` и `Остаток`, вычисляются по полю `Отметка о посещении`.)</span
                    >
                </div>
            </div>
            <div>
                <h3 class="mb-2 text-gray-900 dark:text-gray-300">
                    Выберите клиента и период для отображения отчета
                </h3>
            </div>
            <div class="flex items-center mb-4 space-x-2">
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
            <div class="mb-4" v-if="client">
                <span class="text-lg font-bold">{{ client.fio }}</span> (id - {{ client.id }})              
                <div>
                    <span class="font-bold text-blue-500">Запрос родителей</span> - 
                        <span v-for="wish in client.wishes" :key="wish.id"
                        class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-500 dark:text-indigo-900 whitespace-nowrap mt-2"
                        >
                        <strong>Направление:</strong> {{ wish.class }} * <strong>Кол-во:</strong> {{ wish.prefer_amount_of_classes }} * <strong>Дни:</strong> {{ wish.prefer_day }} * <strong>Время:</strong> {{ wish.prefer_time }}
                        </span>
                </div>
                <div>
                    <span class="font-bold text-blue-500">Примечание</span> - {{ client.comment }}
                </div>
            </div>
            <div class="flex flex-col mt-8" v-if="records.length > 0">
                <div class="overflow-x-auto rounded-lg">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow sm:rounded-lg">
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Направление</TableHeaderCell>
                                        <TableHeaderCell>Запланированно занятий</TableHeaderCell>
                                        <TableHeaderCell>Запланированно занятий по датам</TableHeaderCell>
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
                                            class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-1 rounded dark:bg-indigo-500 dark:text-indigo-900 whitespace-nowrap"
                                            >
                                                {{ moment(item.educationDate).format("ddd DD.MM.YYYY") }}&nbsp;-&nbsp;<span class="bg-blue-400 text-blue-100 px-1.5 py-0.5 rounded-full">{{ item.date_count }}</span>
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
            <div class="p-4 border-l-8 border-red-300" v-else>Данные на указанный период и клиента отсутствуют в расписании. Попробуйте изменить клиента или период.</div>
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