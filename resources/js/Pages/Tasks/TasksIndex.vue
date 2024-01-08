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
import InputLabel from '@/Components/InputLabel.vue';
import { usePermissions } from "@/Composables/permissions";


const { hasRole } = usePermissions();

const toast = useToast();
const props = defineProps({
    tasks: {
        type: Object,
        required: true,
    },
});

// Delete User Modal
const deleteForm = useForm({
    task_id: null
});
const executeForm = useForm({
    comments: "",
    task_id: null
});
const showConfirmDeleteModal = ref(false);
const showExecuteTaskModal = ref(false);
const confirmDelete = (task_id) => {
    showConfirmDeleteModal.value = true;
    deleteForm.task_id = task_id;
};
const confirmExecuteTask = (task_id) => {
    showExecuteTaskModal.value = true;
    executeForm.task_id = task_id;
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
    showExecuteTaskModal.value = false;
};
const deleteTask = () => {
    deleteForm.delete(route("tasks.destroy", deleteForm.task_id), {
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

//Функция исполнения задачи
function executeTask() {
    executeForm.put(route("tasks.update", executeForm.task_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Задача выполнена! Поздравляем!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при сохранении данных о выполнении задача!", {
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
                Список задач для администратора
            </h2>
        </template>

        <div class="mb-4">
            <div
                class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                            Список задач для администратора
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения списка
                            задач для администратора.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('tasks.create')" v-if="hasRole('user')|hasRole('admin')">
                            Добавить задачу
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
                                            <TableHeaderCell>Создано</TableHeaderCell>
                                            <TableHeaderCell>Задача</TableHeaderCell>
                                            <TableHeaderCell>Срок</TableHeaderCell>
                                            <TableHeaderCell>Выполнение</TableHeaderCell>
                                            <TableHeaderCell>Комментарий</TableHeaderCell>
                                            <TableHeaderCell v-if="hasRole('admin')">Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <TableRow v-for="task in tasks" :key="task.id">
                                            <TableDataCell>{{ task.id }}</TableDataCell>
                                            <TableDataCell>{{ task.created_by }}<br /><span>{{ task.created_at }}</span></TableDataCell>
                                            <TableDataCell>{{ task.task }}</TableDataCell>
                                            <TableDataCell>{{ task.executeDate }}</TableDataCell>
                                            <TableDataCell>
                                                <span v-if="task.executed === 1"  class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-cyan-500 dark:text-cyan-900">
                                                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                                    </svg>
                                                    Выполнено
                                                </span> 
                                                <button v-else-if="task.executed !== 1 && hasRole('admin') | hasRole('moderator')" @click="confirmExecuteTask(task.id)" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-pink-500 dark:text-pink-900">
                                                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                                                    </svg>
                                                    Не выполнено
                                                </button>  
                                                <span v-else-if="task.executed !== 1" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-pink-500 dark:text-pink-900">
                                                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                                                    </svg>
                                                    Не выполнено
                                                </span>                                              
                                            </TableDataCell>
                                            <TableDataCell>{{ task.comments }}</TableDataCell>
                                            <TableDataCell v-if="hasRole('admin')">
                                                <PinkButton @click="confirmDelete(task.id)">
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
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-500">
                        Подтвердите удаление задачи!
                    </h2>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <PinkButton @click="deleteTask">Удалить</PinkButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>

        <Modal :show="showExecuteTaskModal" @close="closeModal" :maxWidth="'sm'">
            <div class="p-6">
                <div>
                    <div class="mb-1">
                            <InputLabel for="comments" value="Комментарий" />

                            <textarea id="comments" rows="3" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"                            
                            v-model="executeForm.comments"
                            maxlength="255"
                            autofocus
                            ></textarea>
                        </div>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <PinkButton @click="executeTask">Выполнить задачу</PinkButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
