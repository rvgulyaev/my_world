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
    rooms: {
        type: Array,
        required: true,
    },
})

// Delete User Modal
const deleteForm = useForm({
    room_id: -1
})
const restoreForm = useForm({
    room_id: -1
})
const showConfirmDeleteRoomModal = ref(false)
const confirmDeleteRoom = (id) => {
    deleteForm.room_id = id;
    showConfirmDeleteRoomModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteRoomModal.value = false;
}
const deleteRoom = () => {
    deleteForm.post(route('admin.rooms.terminate', deleteForm.room_id), {
        onSuccess: () => { 
            closeModal(); 
            toast.success("Кабинет успешно удален!", {
                timeout: 2000
            });
        },
        onError: () => {
            closeModal(); 
            toast.error("Ошибка при удалении кабинета!", {
                timeout: 2000
            });
        }
    })
}

const restoreRoom = (room_item) => {
    restoreForm.room_id = room_item.id
    restoreForm.post(route('admin.rooms.restore', restoreForm.room_id), {
        onSuccess: () => { 
            toast.success("Направление успешно восстановлено!", {
                timeout: 2000
            });
        },
        onError: () => {
            toast.error("Ошибка при восстановлении направления!", {
                timeout: 2000
            });
        }
    })
}
</script>

<template>
  <Head title="Корзина списка кабинетов" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Корзина списка кабинетов</h2>
    </template>

           <div class="mb-4">
              <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                 <div class="mb-4 flex items-center justify-between">
                    <div>
                       <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Корзина списка кабинетов</h3>
                       <span class="text-base font-normal text-gray-500">Для восстановления кабинета нажмите кнопку "Восстановить". Для окончательного удаления кабинета нажмите кнопку "Удалить".</span>
                    </div>
                 </div>                 
                <div class="mb-4 flex items-center justify-between">
                    <div></div>
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.rooms.index')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в список кабинетов</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="align-middle inline-block min-w-full">
                          <div class="shadow overflow-hidden pb-3">
                            <div v-if="rooms.length > 0">
                                <Table>
                                    <template #header>
                                        <TableRow>
                                            <TableHeaderCell>Id</TableHeaderCell>
                                            <TableHeaderCell>Имя</TableHeaderCell>
                                            <TableHeaderCell class="text-right">Действия</TableHeaderCell>
                                        </TableRow>
                                    </template>
                                    <template #default>
                                        <template v-for="room in rooms" :key="room.id">
                                            <TableRow>
                                                <TableDataCell>{{ room.id }}</TableDataCell>
                                                <TableDataCell>
                                                <Link :href="route('admin.rooms.edit', room.id)" class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300">
                                                {{ room.name }}
                                                </Link>
                                                </TableDataCell>
                                                <TableDataCell v-if="hasRole('admin')" class="text-right">
                                                    <EmeraldButton @click="restoreRoom(room)">Восстановить</EmeraldButton>  
                                                    <PinkButton @click="confirmDeleteRoom(room.id)">Удалить</PinkButton> 
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
           <Modal :show="showConfirmDeleteRoomModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-700">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление кабинета! Кабинет будет удалено окончательно и безповоротно!
                        </h2>
                    </div>
                    <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                        <DangerButton @click="deleteRoom">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>
</AuthenticatedLayout>
</template>