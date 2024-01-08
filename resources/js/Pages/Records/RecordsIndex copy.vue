<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import RecordsHeaderCell from "@/Components/RecordsHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import RecordsDataCell from "@/Components/RecordsDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PinkButton from "@/Components/PinkButton.vue";
import { useToast } from "vue-toastification";
import InputLabel from '@/Components/InputLabel.vue';
import { usePermissions } from "@/Composables/permissions";


const { hasRole } = usePermissions();

const toast = useToast();
const props = defineProps({
    records: {
        type: Object,
        required: true,
    },
    time_ranges: {
        type: Object,
        required: true
    },
    users: {
        type: Object,
        required: true
    }
});

// Delete User Modal
const deleteForm = useForm({
    record_id: null
});
const showConfirmDeleteModal = ref(false);
const confirmDelete = (record_id) => {
    showConfirmDeleteModal.value = true;
    deleteForm.record_id = record_id;
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
};
const deleteRecord = () => {
    deleteForm.delete(route("records.destroy", deleteForm.task_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Задача успешно удалена!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при удалении задачи!", {
                timeout: 2000
            });
        },
    });
};


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
                <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden sm:rounded-lg">
                                <Table>
                                    <template #header>
                                        <TableRow>
                                            <TableHeaderCell rowspan="2" class="text-center border-r border-gray-500 dark:border-gray-700 bg-gray-300 dark:bg-gray-800">Время</TableHeaderCell>
                                            <TableHeaderCell :colspan="4" class="text-center border-r border-gray-500 dark:border-gray-700 bg-gray-300 dark:bg-gray-800"  v-for="user in users" :key="user.id">{{ user.name }}</TableHeaderCell>
                                        </TableRow>
                                        <TableRow>
                                            <RecordsHeaderCell v-for="user in users" :key="user.id" />
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <TableRow v-for="time in time_ranges" :key="time.id">
                                            <TableDataCell class="dark:border-gray-700">{{ time.name }}</TableDataCell>
                                            <RecordsDataCell v-for="record in records[time.id]" :key="record.index" :record="record" />
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
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-500">
                        Подтвердите удаление записи в расписании!
                    </h2>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <PinkButton @click="deleteTask">Удалить</PinkButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
