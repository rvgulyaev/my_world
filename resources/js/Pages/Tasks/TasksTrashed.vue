<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PinkButton from '@/Components/PinkButton.vue';
import { useToast } from "vue-toastification";
import { usePermissions } from "@/Composables/permissions";
import SearchInput from '@/Components/SearchInput.vue';
import EmeraldButton from '@/Components/EmeraldButton.vue';

const { hasRole } = usePermissions();

const toast = useToast();
const props  = defineProps({
    tasks: {
        type: Array,
        required: true,
    },
    search_task: {
        type: String,
        default: ''
    }
})

// Delete User Modal
const deleteForm = useForm({
    task_id: -1
})
const restoreForm = useForm({
    task_id: -1
})
const showConfirmDeleteTaskModal = ref(false)
const confirmDeleteTask = (id) => {
    deleteForm.task_id = id;
    showConfirmDeleteTaskModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteTaskModal.value = false;
}
const deleteTask = () => {
    deleteForm.post(route('tasks.terminate', deleteForm.task_id), {
        onSuccess: () => { 
            closeModal(); 
            toast.success("Задача успешно удалена!", {
                timeout: 2000
            });
        },
        onError: () => {
            closeModal(); 
            toast.error("Ошибка при удалении задачи!", {
                timeout: 2000
            });
        }
    })
}

const restoreTask = (task) => {
    restoreForm.task_id = task.id
    restoreForm.post(route('tasks.restore', restoreForm.task_id), {
        onSuccess: () => { 
            toast.success("Задача успешно восстановлена!", {
                timeout: 2000
            });
        },
        onError: () => {
            toast.error("Ошибка при восстановлении задачи!", {
                timeout: 2000
            });
        }
    })
}

function task_is_timeout(execute_date) {
    let y = execute_date.split('.')[2]
    let m = execute_date.split('.')[1]
    let d = execute_date.split('.')[0]
    let date1 = new Date(m + '.' + d + '.' + y);
    let date2 = new Date();
    if ((date2 - date1) > 0) {
        return true;
    }
}
</script>

<template>
  <Head title="Корзина списка пользователей" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Корзина списка пользователей</h2>
    </template>

           <div class="mb-4">
              <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                 <div class="mb-4 flex items-center justify-between">
                    <div>
                       <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Корзина списка пользователей</h3>
                       <span class="text-base font-normal text-gray-500">Для восстановления пользователя нажмите кнопку "Восстановить". Для окончательного удаления пользователя нажмите кнопку "Удалить".</span>
                    </div>
                 </div>                 
                <div class="mb-4 flex items-center justify-between">
                       <SearchInput :search_field="'search_task'" :search="props.search_task" :route_link="'tasks.index'" :pholder="'Поиск по задаче...'"/> 
                       <div class="flex-shrink-0">
                            <Link :href="route('tasks.index')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в список задач</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="align-middle inline-block min-w-full">
                          <div class="shadow overflow-hidden">
                             <Table v-if="tasks.length > 0">
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
                                    <template v-for="task in tasks" :key="task.id">
                                            <TableRow v-bind:class="(task_is_timeout(task.executeDate))?'bg-danger-stroked':''">
                                                <TableDataCell>{{ task.id }}</TableDataCell>
                                                <TableDataCell>{{ task.created_by }}<br /><span>{{ task.created_at }}</span></TableDataCell>
                                                <TableDataCell class="max-w-xs overflow-hidden font-semibold text-indigo-700 cursor-pointer" @click="showInfo(task)">{{ task.task }}</TableDataCell>
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
                                                <TableDataCell><div class="w-40 overflow-hidden">{{ task.comments }}</div></TableDataCell>
                                                <TableDataCell v-if="hasRole('admin')">
                                                    <EmeraldButton @click="restoreTask(task)">Восстановить</EmeraldButton>  
                                                    <PinkButton @click="confirmDeleteTask(task.id)">Удалить</PinkButton> 
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                </template>
                             </Table>
                             <span v-else class="dark:text-slate-300">Корзина пуста.</span>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
           <Modal :show="showConfirmDeleteTaskModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-600">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление задачи! Задача будет удалена окончательно и безповоротно!
                        </h2>
                    </div>
                    <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                        <DangerButton @click="deleteTask">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>
</AuthenticatedLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>