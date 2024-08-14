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
import TextInput from '@/Components/TextInput.vue';

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
            <h2 class="leading-tight text-gray-800">
                Справочник направлений
            </h2>
        </template>

        <div class="mb-4">
            <div
                class="p-4 bg-white rounded-lg shadow dark:bg-gray-700 sm:p-6 xl:p-8"
            >
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                            Справочник направлений
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения справочника направлений.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('admin.classes.create')">
                            Добавить направление
                        </PrimaryLink>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                        <div></div>
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.classes.trashed')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в корзину</Link>
                        </div>
                </div>
                <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden shadow sm:rounded-lg">
                                <Table>
                                    <template #header>
                                        <TableRow>
                                            <TableHeaderCell>Id</TableHeaderCell>
                                            <TableHeaderCell>Имя</TableHeaderCell>
                                            <TableHeaderCell>Группа</TableHeaderCell>
                                            <TableHeaderCell>Сортировка</TableHeaderCell>
                                            <TableHeaderCell class="text-right">Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <template v-for="classe in classes" :key="classe.id">
                                            <TableRow>
                                                <TableDataCell>{{ classe.id }}</TableDataCell>
                                                <TableDataCell>
                                                <Link :href="route('admin.classes.edit', classe.id)" class="ml-3 font-bold leading-5 text-indigo-600 text-xm dark:text-indigo-500 hover:text-indigo-300">
                                                {{ classe.name }}
                                                </Link>
                                                </TableDataCell>
                                                <TableDataCell>{{ classe.class_group }}</TableDataCell>
                                                <TableDataCell>{{ classe.order }}</TableDataCell>
                                                <TableDataCell class="text-right">
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
            <div class="p-6 dark:bg-gray-600">
                <div class="flex items-center justify-center">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                        Подтвердите удаление направления!
                    </h2>
                </div>
                <div class="flex items-center justify-center pt-5 mt-6 space-x-2 border-t border-gray-500">
                    <DangerButton @click="deleteTask">Удалить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
