<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage } from "@inertiajs/vue3";
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
const user = usePage().props.auth.user
const showSpinner = ref(false);

const specialists = ref([])
const records = ref([])
const specialist_id = ref()
const dateValue = ref()
const format = 'dd.MM.yyyy'
let filters = {}

async function getReport() {
    showSpinner.value = true;
    if (hasRole('user')) {
        specialist_id.value = user.id
    }
    if (specialist_id.value.id > 0) {
        filters = { 'start_date':dateValue.value[0], 'end_date':dateValue.value[1], 'specialist_id': specialist_id.value.id }
         } else {
        filters = { 'start_date':dateValue.value[0], 'end_date':dateValue.value[1] }
        }
    await axios.post('/api/get_specialists_report', filters)
    .then((response) => {
        showSpinner.value=false;
        records.value = response.data.records
    })
    .catch((e) => {
        showSpinner.value = false;
    })
}

async function getSpecialists() {
    showSpinner.value = true;
    await axios.get('/api/get_specialists_list')
    .then((response) => {
        showSpinner.value = false
        specialists.value = response.data.specialists
        specialist_id.value = response.data.specialists[0]
    })
    .catch((e) => {
        showSpinner.value = false
    })
}


onBeforeMount(() => {
    const startDate = moment(new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()-6)).format('YYYY-MM-DD');
    const endDate =  moment(new Date()).format('YYYY-MM-DD');
    dateValue.value = [startDate, endDate]
    getSpecialists()
})
</script>

<template>
     <Head title="Отчет по специалистам" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="leading-tight text-gray-800">
            Отчет по специалистам
        </h2>
    </template>

    <div class="mb-4">
        <div class="h-screen p-4 bg-white rounded-lg shadow dark:bg-gray-700 sm:p-6 xl:p-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                        Отчет по специалистам
                    </h3>
                    <span class="text-base font-normal text-gray-500" v-if="hasRole('user')">Отчет по проведенным занятиям специалиста <span class="underline">{{ user.name }}</span> за определенный период - по умолчанию период за последнюю(не календарную) неделю. <br/>
                        (Примечание: Значение полей таблицы `Проведено занятий` и `Остаток`, вычисляются по полю `Отметка о посещении` расписания занятий.)</span>
                    <span class="text-base font-normal text-gray-500" v-else>Отчет по проведенным занятиям специалистами за определенный период - по умолчанию период за последнюю(не календарную) неделю. <br/>
                        (Примечание: Значение полей таблицы `Проведено занятий` и `Остаток`, вычисляются по полю `Отметка о посещении` расписания занятий.)</span>
                </div>
            </div>
            <div>
                <h3 class="mb-2 text-gray-900 dark:text-gray-300" v-if="hasRole('user')">
                    Выберите период для отображения отчета
                </h3>
                <h3 class="mb-2 text-gray-900 dark:text-gray-300" v-else>
                    Выберите специалиста и период для отображения отчета
                </h3>
            </div>
            <div class="flex items-center mb-4 space-x-2">
                    <div class="w-72" v-if="hasRole('admin')||hasRole('moderator')">  
                        <VueMultiselect
                            v-model="specialist_id"
                            :options="specialists"
                            :multiple="false"
                            :close-on-select="true"
                            :searchable="true"
                            placeholder="Выберите специалиста"
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
            <div class="flex flex-col mt-8" v-if="records.length > 0">
                <div class="overflow-x-auto rounded-lg">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden shadow sm:rounded-lg">
                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Направление</TableHeaderCell>
                                        <TableHeaderCell>Запланированно занятий</TableHeaderCell>
                                        <TableHeaderCell>Проведено занятий</TableHeaderCell>
                                        <TableHeaderCell>Остаток</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="record in records" :key="record.id">
                                        <TableDataCell>{{ record.class_name }}</TableDataCell>
                                        <TableDataCell>{{ record.sum_count }}</TableDataCell>
                                        <TableDataCell>{{ record.present }}</TableDataCell>
                                        <TableDataCell>{{ record.not_present }}</TableDataCell>
                                    </TableRow>
                                </template>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-l-8 border-red-300" v-else>Данные на указанный период и специалисту отсутствуют в расписании. Попробуйте изменить специалиста или период.</div>
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