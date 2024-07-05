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
    classes_groups: {
        type: Array,
        required: true,
    },
})

// Delete User Modal
const deleteForm = useForm({
    class_group_id: -1
})
const restoreForm = useForm({
    class_group_id: -1
})
const showConfirmDeleteClassesModal = ref(false)
const confirmDeleteClass = (id) => {
    deleteForm.class_group_id = id;
    showConfirmDeleteClassesModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteClassesModal.value = false;
}
const deleteClass = () => {
    deleteForm.post(route('admin.classes_groups.terminate', deleteForm.class_group_id), {
        onSuccess: () => { 
            closeModal(); 
            toast.success("Группа направлений успешно удалено!", {
                timeout: 2000
            });
        },
        onError: () => {
            closeModal(); 
            toast.error("Ошибка при удалении группы направлений!", {
                timeout: 2000
            });
        }
    })
}

const restoreClass = (class_group_item) => {
    restoreForm.class_group_id = class_group_item.id
    restoreForm.post(route('admin.classes_groups.restore', restoreForm.class_group_id), {
        onSuccess: () => { 
            toast.success("Группа направлений успешно восстановлена!", {
                timeout: 2000
            });
        },
        onError: () => {
            toast.error("Ошибка при восстановлении группы направлений!", {
                timeout: 2000
            });
        }
    })
}
</script>

<template>
  <Head title="Корзина списка групп направлений" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="leading-tight text-gray-800 dark:text-gray-200">Корзина списка групп направлений</h2>
    </template>

           <div class="mb-4">
              <div class="p-4 bg-white rounded-lg shadow dark:bg-gray-700 sm:p-6 xl:p-8 ">
                 <div class="flex items-center justify-between mb-4">
                    <div>
                       <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">Корзина списка групп направлений</h3>
                       <span class="text-base font-normal text-gray-500">Для восстановления группы направлений нажмите кнопку "Восстановить". Для окончательного удаления группы направлений нажмите кнопку "Удалить".</span>
                    </div>
                 </div>                 
                <div class="flex items-center justify-between mb-4">
                    <div></div>
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.classes_groups.index')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в список групп направлений</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="inline-block min-w-full align-middle">
                          <div class="pb-3 overflow-hidden shadow">
                            <div v-if="classes_groups.length > 0">
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
                                                <TableDataCell>{{ class_group.name }}</TableDataCell>
                                                <TableDataCell v-if="hasRole('admin')" class="text-right">
                                                    <EmeraldButton @click="restoreClass(class_group)">Восстановить</EmeraldButton>  
                                                    <PinkButton @click="confirmDeleteClass(class_group.id)">Удалить</PinkButton> 
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
           <Modal :show="showConfirmDeleteClassesModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-700">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление группы направлений! Группа направлений будет удалена окончательно и безповоротно!
                        </h2>
                    </div>
                    <div class="flex items-center justify-center pt-5 mt-6 space-x-2 border-t border-gray-500">
                        <DangerButton @click="deleteClass">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>
</AuthenticatedLayout>
</template>