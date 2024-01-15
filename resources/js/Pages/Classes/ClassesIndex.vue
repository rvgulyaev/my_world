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
import { useToast } from "vue-toastification";

const toast = useToast();
const props = defineProps({
    classes: {
        type: Object,
        required: true,
    },
});

// Delete User Modal
const deleteForm = useForm({
    class_id: {
        type: Number,
        required: true
    }
});
const showConfirmDeleteModal = ref(false);

const confirmDelete = (class_id) => {
    showConfirmDeleteModal.value = true;
    deleteForm.class_id = class_id;
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
};
const deleteTask = () => {
    deleteForm.delete(route("admin.classes.destroy", deleteForm.class_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Тип занятий успешно удален!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при удалении типа занятий!", {
                timeout: 2000
            });
        },
    });
};

</script>

<template>
    <Head title="Справочник типов занятий" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 leading-tight">
                Справочник типов занятий
            </h2>
        </template>

        <div class="mb-4">
            <div
                class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                            Справочник типов занятий
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения справочника типов занятий.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('admin.classes.create')">
                            Добавить тип занятий
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
                                            <TableHeaderCell>Id</TableHeaderCell>
                                            <TableHeaderCell>Имя</TableHeaderCell>
                                            <TableHeaderCell>Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <template v-for="classe in classes" :key="classe.id">
                                            <TableRow>
                                                <TableDataCell>{{ classe.id }}</TableDataCell>
                                                <TableDataCell>
                                                <Link :href="route('admin.classes.edit', classe.id)" class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300">
                                                {{ classe.name }}
                                                </Link>
                                                </TableDataCell>
                                                <TableDataCell>
                                                    <PinkButton @click="confirmDelete(classe.id)">
                                                        Удалить
                                                    </PinkButton>
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
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
                        Подтвердите удаление типа занятий!
                    </h2>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <DangerButton @click="deleteTask">Удалить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
