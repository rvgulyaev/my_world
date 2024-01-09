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
import EmeraldButton from "@/Components/EmeraldButton.vue";


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
                                            <TableDataCell class="max-w-md overflow-hidden">{{ task.task }}</TableDataCell>
                                            <TableDataCell>{{ task.executeDate }}</TableDataCell>
                                            <TableDataCell>
                                                <EmeraldButton v-if="task.executed === 1" enabled="false" class="cursor-not-allowed">
                                                    Выполнено
                                                </EmeraldButton>
                                                <PinkButton v-else-if="task.executed !== 1 && hasRole('admin') | hasRole('moderator')" @click="confirmExecuteTask(task.id)">
                                                    Не выполнено
                                                </PinkButton>  
                                                <PinkButton v-else-if="task.executed !== 1" enabled="false" class="cursor-not-allowed">
                                                    Не выполнено
                                                </PinkButton>                                             
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
                    <DangerButton @click="deleteTask">Удалить</DangerButton>
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
                    <DangerButton @click="executeTask">Выполнить задачу</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
