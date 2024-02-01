<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import { ref, onMounted, watch } from "vue";
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
import Spinner from '@/Components/Spinner.vue';
import DangerButton from "@/Components/DangerButton.vue";
import moment from 'moment/min/moment-with-locales';
import DropdownButton from '@/Components/DropdownButton.vue';
import DropdownMenu from 'v-dropdown';
import InputLabel from '@/Components/InputLabel.vue'

const { hasRole } = usePermissions();
const showSpinner = ref(false);
const toast = useToast();
const user = usePage().props.auth.user
const showConfirmDeleteModal = ref(false);
const showCommentModal = ref(false);
const dropdown = ref(null)
const comment = ref({
    record_id: null,
    text: ''
})

moment.locale('ru')

let education_date = ref()
let user_id = ref()
let users = ref({})
let records = ref([])
let record_to_delete = ref(-1)
let filters = {};

function confirmDeleteRecord (record_id) {
    showConfirmDeleteModal.value = true;
    record_to_delete.value = record_id;
    console.log(record_to_delete)
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
    showCommentModal.value = false;
};
async function deleteRecord () {
    closeModal();
    showSpinner.value = true;
    await axios.post('/api/delete_record', { 'record_id': record_to_delete.value })
    .then((response) => {
        showSpinner.value = false;
        let index = records.value.findIndex((x) => x.id === record_to_delete.value);
        records.value.splice(index, 1)
        toast.success("Отметка о посещении клиента успешно обновлена!", {
                timeout: 2000
            });
    })
    .catch((e) => {
        showSpinner.value = false;
        console.log(e)
        toast.error("Ошибка при удалении записи из расписания! - ", {
                timeout: 2000
            });
    })
};

function checkUser(userid) {
    filters.user_id = userid
    getRecords();
}

watch(education_date, (value) => {
    filters.education_date = education_date.value
    getRecords();
});

async function getRecords() {
    showSpinner.value = true;
    await axios.post('/api/get_records', filters)
    .then((response) => {
        showSpinner.value=false;
        users.value = response.data.users
        records.value = response.data.records
        user_id.value = response.data.user_id
        education_date.value = response.data.education_date
    })
    .catch((e) => {
        showSpinner.value = false;
    })
}

async function setPresent(id) {
    showSpinner.value = true;
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

function commentRecord(record_id, index) {
    if (dropdown.value[index].visible) {
        dropdown.value[index].close()
    }
    showCommentModal.value = true
    comment.value.id = record_id
}

async function storeCommentRecord() {
    closeModal()
    await axios.post('/api/store_record_comment', {'record_id': comment.value.id, 'comment': comment.value.text})
    .then((response) => {
        showSpinner.value = false
        let index = records.value.findIndex((x) => x.id === comment.value.id)
        records.value[index].comment = comment.value.text
        toast.success("Примечание успешно сохранено!", {
            timeout: 2000
        })
    })
    .catch((e) => {
        showSpinner.value = false
        console.log(e)
        toast.error("Ошибка при сохранении примечания!")
    })
}

onMounted(() => {    
    getRecords();
})

</script>

<template>
    <Head title="Расписание занятий" />

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
                        <span class="text-base font-normal text-gray-500"  v-if="hasRole('user')">Расписание занятий для <span class="underline">{{ user.name }}</span>.</span>
                        <span class="text-base font-normal text-gray-500"  v-else>Раздел предназначен для ведения расписания занятий.</span>
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('records.create')" v-if="hasRole('moderator')|hasRole('admin')">
                            Добавить запись
                        </PrimaryLink>
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-between">
                    <div >
                        <h3 class="text-gray-900 dark:text-gray-300 mb-2" v-if="hasRole('user')">Выберите дату для отображения расписания</h3>
                        <h3 class="text-gray-900 dark:text-gray-300 mb-2" v-else>Выберите дату и специалиста для отображения расписания</h3>
                        <div class="flex items-center">
                            <TextInput type="date" v-model="education_date" class="mr-7"/>
                            <div v-if="hasRole('admin')||hasRole('moderator')">                            
                                <template v-for="user in users" :key="user.id">
                                    <ButtonActive v-if="user.id === user_id">{{ user.name }}</ButtonActive>
                                    <ButtonInActive v-else @click="checkUser(user.id)">{{ user.name }}</ButtonInActive>
                                </template>
                            </div>
                        </div>                          
                    </div>
                </div>
                <div class="flex flex-col mt-8" v-if="records&&records.length>0">
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
                                            <TableHeaderCell>Примечание</TableHeaderCell>
                                            <TableHeaderCell v-if="hasRole('moderator')|hasRole('admin')">Действие</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <TableRow v-for="(record, index) in records" :key="record.id">
                                            <TableDataCell v-if="record.start_time !== null && record.end_time !== null">{{ moment(record.start_time).format('H:mm') }} - {{ moment(record.end_time).format('H:mm') }}</TableDataCell>
                                            <TableDataCell v-else></TableDataCell>
                                            <TableDataCell>{{ record.client_name }}</TableDataCell>
                                            <TableDataCell>{{ record.class_name }}</TableDataCell>
                                            <TableDataCell>{{ record.room_name }}</TableDataCell>
                                            <TableDataCell v-if="hasRole('moderator')|hasRole('admin')">
                                                <PinkButton v-if="record.is_present === 0" @click="setPresent(record.id)">
                                                    Не был
                                                </PinkButton>
                                                <EmeraldButton v-else-if="record.is_present === 1" @click="setPresent(record.id)">
                                                    Был
                                                </EmeraldButton>
                                            </TableDataCell>
                                            <TableDataCell v-else>
                                                <PinkButton v-if="record.is_present === 0" enabled="false" class="cursor-not-allowed">
                                                    Не был
                                                </PinkButton>
                                                <EmeraldButton v-else-if="record.is_present === 1" enabled="false" class="cursor-not-allowed">
                                                    Был
                                                </EmeraldButton>
                                            </TableDataCell>
                                            <TableDataCell>{{ record.comment }}</TableDataCell>
                                            <TableDataCell v-if="hasRole('moderator')|hasRole('admin')">
                                                <div class="flex">
                                                    <PinkButton v-if="record.id > 0&&hasRole('moderator')|hasRole('admin')" @click="confirmDeleteRecord(record.id)">
                                                        Удалить
                                                    </PinkButton>
                                                    <DropdownMenu class="bg-pink-300 dark:bg-pink-500 dark:text-pink-900 rounded ml-0 px-2 cursor-pointer" ref="dropdown">
                                                        <template #trigger>
                                                        ...
                                                        </template>                                                        
                                                        <ul class="dark:bg-gray-700 dark:border-gray-700">
                                                            <li>
                                                                <DropdownButton @click="commentRecord(record.id, index)">
                                                                    Добавить / изменить примечание
                                                                </DropdownButton>
                                                            </li>
                                                        </ul>
                                                    </DropdownMenu>
                                                </div>
                                            </TableDataCell>        
                                        </TableRow>
                                    </template>
                                </Table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-l-8 p-4 border-red-300" v-else>Данные на указанный период отсутствуют в расписании. Попробуйте изменить значения фильтра.</div>
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

        <Modal :show="showCommentModal" @close="closeModal" :maxWidth="'sm'">
            <div class="p-6 dark:bg-gray-600">
                <div class="mb-1">
                        <InputLabel for="comment" value="Примечание" />

                        <textarea id="comment" rows="3" 
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"                            
                        v-model="comment.text"
                        maxlength="255"
                        autofocus
                        required
                        ></textarea>
                    </div>
                <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                    <DangerButton @click="storeCommentRecord">Сохранить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
        <Spinner :showSpinner="showSpinner" />    
    </AuthenticatedLayout>
</template>
