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
import SearchInput from '@/Components/SearchInput.vue';
import EmeraldButton from '@/Components/EmeraldButton.vue';

const toast = useToast();
const props  = defineProps({
    users: {
        type: Array,
        required: true,
    },
    search_user: {
        type: String,
        default: ''
    }
})

// Delete User Modal
const deleteForm = useForm({
    user_id: -1
})
const restoreForm = useForm({
    user_id: -1
})
const showConfirmDeleteUserModal = ref(false)
const confirmDeleteUser = (id) => {
    deleteForm.user_id = id;
    showConfirmDeleteUserModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteUserModal.value = false;
}
const deleteUser = () => {
    deleteForm.post(route('admin.users.terminate', deleteForm.user_id), {
        onSuccess: () => { 
            closeModal(); 
            toast.success("Пользователь успешно удален!", {
                timeout: 2000
            });
        },
        onError: () => {
            closeModal(); 
            toast.error("Ошибка при удалении пользователя!", {
                timeout: 2000
            });
        }
    })
}

const restoreUser = (user) => {
    restoreForm.user_id = user.id
    restoreForm.post(route('admin.users.restore', restoreForm.user_id), {
        onSuccess: () => { 
            toast.success("Пользователь успешно восстановлен!", {
                timeout: 2000
            });
        },
        onError: () => {
            toast.error("Ошибка при восстановлении пользователя!", {
                timeout: 2000
            });
        }
    })
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
                       <SearchInput :search_field="'search_user_fio'" :search="props.search_user" :route_link="'users.index'" :pholder="'Поиск по ФИО...'"/> 
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.users.index')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в список пользователей</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="align-middle inline-block min-w-full">
                          <div class="shadow overflow-hidden">
                             <Table v-if="users.length > 0">
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Id</TableHeaderCell>
                                        <TableHeaderCell>ФИО</TableHeaderCell>
                                        <TableHeaderCell>Имя пользователя</TableHeaderCell>
                                        <TableHeaderCell>Телефон</TableHeaderCell>
                                        <TableHeaderCell>Специалист</TableHeaderCell>
                                        <TableHeaderCell>Права</TableHeaderCell>
                                        <TableHeaderCell>Удален</TableHeaderCell>
                                        <TableHeaderCell>Действия</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="user in users" :key="user.id">
                                        <TableDataCell>{{ user.id }}</TableDataCell>
                                        <TableDataCell>
                                            <Link :href="route('admin.users.edit', user.id)" class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300">{{ user.name }}</Link>    
                                        </TableDataCell>
                                        <TableDataCell>{{ user.username }}</TableDataCell>
                                        <TableDataCell>{{ user.phone }}</TableDataCell>
                                        <TableDataCell>
                                            <svg v-if="user.specialist === 1" class="w-6 h-6 mx-auto text-cyan-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                            </svg>
                                            <svg v-if="user.specialist !== 1" class="w-6 h-6 mx-auto text-red-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
                                            </svg>
                                        </TableDataCell>
                                        <TableDataCell>
                                            <span v-for="(role, index) in user.roles" :key="index" 
                                            class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-500 dark:text-indigo-900">
                                                {{ role }}
                                            </span>
                                        </TableDataCell>
                                        <TableDataCell>{{ user.deleted_at }}</TableDataCell>
                                        <TableDataCell>
                                            <EmeraldButton @click="restoreUser(user)">Восстановить</EmeraldButton>  
                                            <PinkButton @click="confirmDeleteUser(user.id)">Удалить</PinkButton>                                          
                                        </TableDataCell>
                                    </TableRow>
                                </template>
                             </Table>
                             <span v-else class="dark:text-slate-300">Корзина пуста.</span>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
           <Modal :show="showConfirmDeleteUserModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-600">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление пользователя! Пользователь буде удален окончательно и безповоротно!
                        </h2>
                    </div>
                    <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                        <DangerButton @click="deleteUser">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>
</AuthenticatedLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>