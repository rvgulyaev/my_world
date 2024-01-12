<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PinkButton from "@/Components/PinkButton.vue";
import SearchInput from "@/Components/SearchInput.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    clients: {
        type: Object,
        required: true,
    },
    search_client: {
        type: String,
        default: ''
    }
});


// Delete User Modal
const deleteForm = useForm({
    client_id: null
});
const showConfirmDeleteModal = ref(false);
const confirmDelete = (client_id) => {
    showConfirmDeleteModal.value = true;
    deleteForm.client_id = client_id;
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
};
const deleteClient = () => {
    deleteForm.delete(route("clients.destroy", deleteForm.client_id), {
        onSuccess: () => closeModal(),
    });
};


</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 leading-tight">
                Список клиентов
            </h2>
        </template>

        <div class="mb-4">
            <div
                class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                            Список клиентов
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения списка
                            клиентов.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('clients.create')">
                            Добавить клиента
                        </PrimaryLink>
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-between">
                       <SearchInput :search_field="'search_client_fio'" :search="props.search_client" :route_link="'clients.index'" :pholder="'Поиск по ФИО...'"/> 
                </div>
                <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                        <div class="align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden sm:rounded-lg">
                                <Table>
                                    <template #header>
                                        <TableRow>
                                            <TableHeaderCell>Id</TableHeaderCell>
                                            <TableHeaderCell>ФИО</TableHeaderCell>
                                            <TableHeaderCell>Дата рождения</TableHeaderCell>
                                            <TableHeaderCell>Диагноз</TableHeaderCell>
                                            <TableHeaderCell>Противопоказания</TableHeaderCell>
                                            <TableHeaderCell>Создано</TableHeaderCell>
                                            <TableHeaderCell>Обновлено</TableHeaderCell>
                                            <TableHeaderCell>Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <TableRow v-for="client in clients.data" :key="client.id">
                                            <TableDataCell>{{ client.id }}</TableDataCell>
                                            <TableDataCell>
                                                <Link :href="route('clients.edit',client.id)"
                                                    class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300"
                                                >{{ client.fio }}
                                                </Link>
                                            </TableDataCell>
                                            <TableDataCell>{{ client.burndate }}</TableDataCell>
                                            <TableDataCell>{{ client.diagnos }}</TableDataCell>
                                            <TableDataCell>{{ client.contras }}</TableDataCell>
                                            <TableDataCell>{{ client.created_by }}<br /><span>{{ client.created_at }}</span></TableDataCell>
                                            <TableDataCell>{{ client.updated_by }}<br /><span>{{ client.updated_at }}</span></TableDataCell>
                                            <TableDataCell>
                                                <PinkButton @click="confirmDelete(client.id)">
                                                    Удалить
                                                </PinkButton>
                                            </TableDataCell>
                                        </TableRow>
                                    </template>
                                </Table>
                                <pagination class="mt-6" :links="clients.links" />
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
                        Подтвердите удаление клиента!
                    </h2>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <DangerButton @click="deleteClient">Удалить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
