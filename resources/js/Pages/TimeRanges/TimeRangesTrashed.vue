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
import Pagination from "@/Components/Pagination.vue";

const { hasRole } = usePermissions();

const toast = useToast();
const props  = defineProps({
    time_ranges: {
        type: Array,
        required: true,
    },
})

// Delete User Modal
const deleteForm = useForm({
    time_range_id: -1
})
const restoreForm = useForm({
    time_range_id: -1
})
const showConfirmDeleteModal = ref(false)
const confirmDelete = (id) => {
    deleteForm.time_range_id = id;
    showConfirmDeleteModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteModal.value = false;
}
const deleteItem = () => {
    deleteForm.post(route('admin.time-ranges.terminate', deleteForm.time_range_id), {
        onSuccess: () => { 
            closeModal(); 
            toast.success("Временной диапазон успешно удален!", {
                timeout: 2000
            });
        },
        onError: () => {
            closeModal(); 
            toast.error("Ошибка при удалении временного диапазона!", {
                timeout: 2000
            });
        }
    })
}

const restoreItem = (item) => {
    restoreForm.time_range_id = item.id
    restoreForm.post(route('admin.time-ranges.restore', restoreForm.time_range_id), {
        onSuccess: () => { 
            toast.success("Временной диапазон успешно восстановлен!", {
                timeout: 2000
            });
        },
        onError: () => {
            toast.error("Ошибка при восстановлении времемнного диапазона!", {
                timeout: 2000
            });
        }
    })
}
</script>

<template>
  <Head title="Корзина списка временных диапазонов" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Корзина списка временных диапазонов</h2>
    </template>

           <div class="mb-4">
              <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                 <div class="mb-4 flex items-center justify-between">
                    <div>
                       <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Корзина списка временных диапазонов</h3>
                       <span class="text-base font-normal text-gray-500">Для восстановления временного диапазона нажмите кнопку "Восстановить". Для окончательного временного диапазона кабинета нажмите кнопку "Удалить".</span>
                    </div>
                 </div>                 
                <div class="mb-4 flex items-center justify-between">
                    <div></div>
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.time-ranges.index')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в список временных диапазонов</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="align-middle inline-block min-w-full">
                          <div class="shadow overflow-hidden pb-3">
                            <div v-if="time_ranges.length > 0">
                                <Table>
                                    <template #header>
                                        <TableRow>
                                            <TableHeaderCell>Id</TableHeaderCell>
                                            <TableHeaderCell>Имя</TableHeaderCell>
                                            <TableHeaderCell>Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <template v-for="time_range in time_ranges" :key="time_range.id">
                                            <TableRow>
                                                <TableDataCell>{{ time_range.id }}</TableDataCell>
                                                <TableDataCell>
                                                <Link :href="route('admin.time-ranges.edit', time_range.id)" class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300">
                                                {{ time_range.name }}
                                                </Link>
                                                </TableDataCell>
                                                <TableDataCell v-if="hasRole('admin')" class="text-right">
                                                    <EmeraldButton @click="restoreItem(time_range)">Восстановить</EmeraldButton>  
                                                    <PinkButton @click="confirmDelete(time_range.id)">Удалить</PinkButton> 
                                                </TableDataCell>
                                            </TableRow>
                                        </template>
                                    </template>
                                </Table>
                             </div>
                             <span v-else class="dark:text-slate-300">Корзина пуста.</span>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
           <Modal :show="showConfirmDeleteModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-700">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление временного диапазона! Временной диапазон будет удален окончательно и безповоротно!
                        </h2>
                    </div>
                    <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                        <DangerButton @click="deleteItem">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>
</AuthenticatedLayout>
</template>