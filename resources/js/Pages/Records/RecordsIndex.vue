<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head} from "@inertiajs/vue3";
import { ref } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PinkButton from "@/Components/PinkButton.vue";
import EmeraldButton from "@/Components/EmeraldButton.vue";
import { useToast } from "vue-toastification";
import { usePermissions } from "@/Composables/permissions";
import ButtonInActive from "@/Components/ButtonInActive.vue";
import TextInput from "@/Components/TextInput.vue";
import ButtonActive from "@/Components/ButtonActive.vue";
import { watch } from "vue";
import { onMounted } from "vue";
import Spinner from '@/Components/Spinner.vue';
import DangerButton from "@/Components/DangerButton.vue";

const { hasRole } = usePermissions();

const showSpinner = ref(false);

const toast = useToast();

let education_date = ref(new Date().toISOString().split('T')[0])
let user_id = ref(-1)
let users = ref({})
let records = ref({})
let record_to_delete = ref(-1)

const showConfirmDeleteModal = ref(false);
function confirmDeleteRecord (record_id) {
    showConfirmDeleteModal.value = true;
    record_to_delete.value = record_id;
    console.log(record_to_delete)
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
};
async function deleteRecord () {
    closeModal();
    showSpinner.value = true;
    await axios.post('/api/delete_record', { 'record_id': record_to_delete.value })
    .then((response) => {
        showSpinner.value = false;
        let index = records.value.findIndex((x) => x.id === record_to_delete.value);
        records.value[index].class_name = null;
        records.value[index].client_name = null;
        records.value[index].id = null;
        records.value[index].room_name = null;
        records.value[index].is_present = null;
        toast.success("Отметка о посещении клиента успешно обновлена!", {
                timeout: 2000
            });
    })
    .catch((e) => {
        showSpinner.value = false;
        toast.error("Ошибка при удалении записи из расписания! - ", {
                timeout: 2000
            });
    })
};

function checkUser(userid) {
    user_id.value = userid
    getRecords();
}

watch(education_date, (value) => {
    getRecords();
});

async function getRecords() {
    showSpinner.value = true;
    let filters = {};
    if (user_id.value > 0) {
        filters = { 'education_date':education_date.value, 'user_id': user_id.value }
         } else {
        filters = { 'education_date':education_date.value }
        }
    await axios.post('/api/get_records', filters)
    .then((response) => {
        showSpinner.value=false;
        users.value = response.data.users
        records.value = response.data.records
        user_id.value = response.data.user_id
    })
    .catch((e) => {
        showSpinner.value = false;
    })
}

async function setPresent(id) {
    showSpinner.value = true;
    console.log(id);
    await axios.post('/api/set_is_present', { 'record_id': id })
    .then((response) => {
            showSpinner.value = false;
            let index = records.value.findIndex((x) => x.id === id);
            records.value[index].is_present = response.data.is_present;
            toast.success("Отметка о посещении клиента успешно обновлена!", {
                timeout: 2000
            });
        })
    .catch((e) =>{
            showSpinner.value = false;
            toast.error("Ошибка при обновлении отметки о посещении клиента! - ", {
                timeout: 2000
            });
    });
}

onMounted(() => {
    getRecords();
})

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 leading-tight">
                Расписание занятий
            </h2>
        </template>

        <div class="mb-4">
            <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                            Расписание занятий
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения расписания занятий.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('records.create')" v-if="hasRole('moderator')|hasRole('admin')">
                            Добавить запись
                        </PrimaryLink>
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-gray-900 dark:text-gray-300 mb-2">
                            Выберите дату и специалиста для отображения расписания
                        </h3>
                        <div>                            
                        <TextInput type="date" v-model="education_date" class="mr-7"/>
                        
                        <template v-for="user in users" :key="user.id">
                            <ButtonActive v-if="user.id === user_id">{{ user.name }}</ButtonActive>
                            <ButtonInActive v-else @click="checkUser(user.id)">{{ user.name }}</ButtonInActive>
                        </template>
                        </div>                        
                    </div>
                </div>
                <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden sm:rounded-lg">
                                <Table>
                                    <template #header>
                                        <TableRow>
                                            <TableHeaderCell>Время</TableHeaderCell>
                                            <TableHeaderCell>Клиент</TableHeaderCell>
                                            <TableHeaderCell>Направление</TableHeaderCell>
                                            <TableHeaderCell>Кабинет</TableHeaderCell>
                                            <TableHeaderCell>Отметка о посещении</TableHeaderCell>
                                            <TableHeaderCell v-if="hasRole('moderator')|hasRole('admin')">Действие</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <TableRow v-for="record in records" :key="record.id">
                                            <TableDataCell>{{ record.time_range_name }}</TableDataCell>
                                            <TableDataCell>{{ record.client_name }}</TableDataCell>
                                            <TableDataCell>{{ record.class_name }}</TableDataCell>
                                            <TableDataCell>{{ record.room_name }}</TableDataCell>
                                            <TableDataCell>
                                                <PinkButton v-if="record.is_present === 0" @click="setPresent(record.id)">
                                                    Не был
                                                </PinkButton>
                                                <EmeraldButton v-else-if="record.is_present === 1" @click="setPresent(record.id)">
                                                    Был
                                                </EmeraldButton>
                                            </TableDataCell>
                                            <TableDataCell>
                                                <PinkButton v-if="record.id > 0&&hasRole('moderator')|hasRole('admin')" @click="confirmDeleteRecord(record.id)">
                                                    Удалить
                                                </PinkButton>
                                            </TableDataCell>        
                                        </TableRow>
                                    </template>
                                </Table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="showConfirmDeleteModal" @close="closeModal" :maxWidth="'sm'">
            <div class="p-6 dark:bg-gray-600">
                <div class="flex items-center justify-center">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                        Подтвердите удаление записи в расписании!
                    </h2>
                </div>
                <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                    <DangerButton @click="deleteRecord">Удалить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
        <Spinner :showSpinner="showSpinner" />    
    </AuthenticatedLayout>
</template>
