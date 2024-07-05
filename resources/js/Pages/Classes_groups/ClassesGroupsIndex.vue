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
    classes_groups: {
        type: Object,
        required: true,
    },
});

// Delete User Modal
const deleteForm = useForm({
    class_group_id: {
        type: Number,
        required: true
    }
});
const showConfirmDeleteModal = ref(false);

const confirmDelete = (class_group_id) => {
    showConfirmDeleteModal.value = true;
    deleteForm.class_group_id = class_group_id;
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
};
const deleteTask = () => {
    deleteForm.delete(route("admin.classes_groups.destroy", deleteForm.class_group_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Группа направлений успешно удалена!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при удалении группы направлений!", {
                timeout: 2000
            });
        },
    });
};

</script>

<template>
    <Head title="Справочник групп направлений" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="leading-tight text-gray-800">
                Справочник групп направлений
            </h2>
        </template>

        <div class="mb-4">
            <div
                class="p-4 bg-white rounded-lg shadow dark:bg-gray-700 sm:p-6 xl:p-8"
            >
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                            Справочник групп направлений
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения справочника групп направлений.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('admin.classes_groups.create')">
                            Добавить группу направлений
                        </PrimaryLink>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                        <div></div>
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.classes_groups.trashed')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в корзину</Link>
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
                                            <TableHeaderCell class="text-right">Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <template v-for="class_group in classes_groups" :key="class_group.id">
                                            <TableRow>
                                                <TableDataCell>{{ class_group.id }}</TableDataCell>
                                                <TableDataCell>
                                                <Link :href="route('admin.classes_groups.edit', class_group.id)" class="ml-3 font-bold leading-5 text-indigo-600 text-xm dark:text-indigo-500 hover:text-indigo-300">
                                                {{ class_group.name }}
                                                </Link>
                                                </TableDataCell>
                                                <TableDataCell class="text-right">
                                                    <PinkButton @click="confirmDelete(class_group.id)">
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
                        Подтвердите удаление группы направлений!
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
