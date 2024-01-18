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
import PrimaryLink from '@/Components/PrimaryLink.vue';
import { useToast } from "vue-toastification";
import SearchInput from '@/Components/SearchInput.vue';
import DropdownButton from '@/Components/DropdownButton.vue';
import DropdownMenu from 'v-dropdown';

const toast = useToast();
const props  = defineProps({
    users: {
        type: Object,
        required: true,
    },
    search_user: {
        type: String,
        default: ''
    }
})
const dropdown = ref(null)
// Delete User Modal
const deleteForm = useForm({
    user_id: -1
})
const showConfirmDeleteUserModal = ref(false)
const confirmDeleteUser = (id) => {
    deleteForm.user_id = id;
    showConfirmDeleteUserModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteUserModal.value = false;
    showInfoModal.value = false;
}
const deleteUser = () => {
    deleteForm.delete(route('admin.users.destroy', deleteForm.user_id), {
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

const showInfoModal = ref(false)
let userInfo = ref({})

const showUserInfo = (user, index) => {
    if (dropdown.value[index].visible) {
        dropdown.value[index].close()
    }
    showInfoModal.value = true
    userInfo.value = user
}

const banForm = useForm({
    user_id: Number
})
function banUser (user_id, index){
    if (dropdown.value[index].visible) {
        dropdown.value[index].close()
    }
    banForm.user_id = user_id
    banForm.post(route('admin.users.ban', banForm.user_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Пользователь заблокирован!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при блокировке пользователя!", {
                timeout: 2000
            });
        },
    });
}
function unBanUser (user_id, index){
    if (dropdown.value[index].visible) {
        dropdown.value[index].close()
    }
    banForm.user_id = user_id
    banForm.post(route('admin.users.unban', banForm.user_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Пользователь разблокирован!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при разблокировке пользователя!", {
                timeout: 2000
            });
        },
    });
}
</script>

<template>
  <Head title="Dashboard" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Список пользователей</h2>
    </template>

           <div class="mb-4">
              <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                 <div class="mb-4 flex items-center justify-between">
                    <div class="mr-20">
                       <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Список пользователей</h3>
                       <span class="text-base font-normal text-gray-500">* Пользователи помеченные галочкой "Специалист" используются при составлении расписания занятий. Удаление пользователей происходит в "Корзину" с возможностью восстановления. Для окончательного удаления пользователя перейдите в "Корзину", нажав на ссылку "ПЕРЕЙТИ В КОРЗИНУ".</span>
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('admin.users.create')">Добавить пользователя</PrimaryLink>
                    </div>
                 </div>                 
                <div class="mb-4 flex items-center justify-between">
                       <SearchInput :search_field="'search_user_fio'" :search="props.search_user" :route_link="'admin.users.index'" :pholder="'Поиск по ФИО...'"/> 
                       <div class="flex-shrink-0">
                            <Link :href="route('admin.users.trashed')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в корзину</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="align-middle inline-block min-w-full">
                          <div class="shadow overflow-hidden">
                             <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Id</TableHeaderCell>
                                        <TableHeaderCell>ФИО</TableHeaderCell>
                                        <TableHeaderCell>Имя пользователя</TableHeaderCell>
                                        <TableHeaderCell>Телефон</TableHeaderCell>
                                        <TableHeaderCell>Специалист</TableHeaderCell>
                                        <TableHeaderCell>Права</TableHeaderCell>
                                        <TableHeaderCell>Бан с</TableHeaderCell>
                                        <TableHeaderCell>Действия</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <template v-for="(user, index) in users" :key="user.id">
                                    <TableRow v-bind:class="(user.banned_at)?'bg-danger-stroked':''">
                                        <TableDataCell>{{ user.id }}</TableDataCell>
                                        <TableDataCell>
                                            <Link :href="route('admin.users.edit', user.id)" class="ml-3 text-xm leading-5 font-bold" v-bind:class="(user.banned_at)?'text-rose-700 dark:text-rose-500 hover:text-rose-300':'text-indigo-600 dark:text-indigo-500 hover:text-indigo-300'">{{ user.name }}</Link>    
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
                                        <TableDataCell>{{ user.banned_at }}</TableDataCell>
                                        <TableDataCell>
                                        <div class="flex">
                                            <PinkButton @click="confirmDeleteUser(user.id)">Удалить</PinkButton> 
                                            <DropdownMenu class="bg-pink-300 dark:bg-pink-500 dark:text-pink-900 rounded ml-0 px-2 cursor-pointer" ref="dropdown">
                                            <template #trigger>
                                            ...
                                            </template>                                                        
                                            <ul class="dark:bg-gray-700 dark:border-gray-700">
                                                <li>
                                                    <DropdownButton v-if="!user.banned_at" @click="banUser(user.id, index)">
                                                        Заблокировать
                                                    </DropdownButton>
                                                    <DropdownButton v-else @click="unBanUser(user.id, index)">
                                                        Разблокировать
                                                    </DropdownButton>
                                                </li>
                                                <li>
                                                    <DropdownButton @click="showUserInfo(user, index)">
                                                        Подробнее
                                                    </DropdownButton>
                                                </li>
                                            </ul>
                                            </DropdownMenu>
                                        </div>
                            
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
           <Modal :show="showConfirmDeleteUserModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-600">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление пользователя!
                        </h2>
                    </div>
                    <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                        <DangerButton @click="deleteUser">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>

            <Modal :show="showInfoModal" @close="closeModal" :maxWidth="'sm'">
            <div class="p-6">
                <div>
                    <div class="mb-1">
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">ФИО:</span>
                                <div>{{ userInfo.name }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Имя пользователя:</span>
                                <div>{{ userInfo.username }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Телефон:</span>
                                <div>{{ userInfo.phone }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span>{{ (userInfo.specialist) ? 'Пользователь является специалистом' : 'Пользователь не является специалистом' }}</span>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Статус:</span>
                                <div>{{  (userInfo.banned_at !== null) ? 'Пользователь забанен с ' + userInfo.banned_at : 'Учетная запись пользователя активна' }}</div>
                            </div>
                            <div>
                                <span class="uppercase font-bold">Роли пользователя:</span>
                                <div>
                                    <span v-for="(role, index) in userInfo.roles" :key="index" 
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-500 dark:text-indigo-900">
                                        {{ role }}
                                    </span>
                                </div>

                            </div>
                        </div>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <SecondaryButton @click="closeModal">Закрыть</SecondaryButton>
                </div>
            </div>
        </Modal>
</AuthenticatedLayout>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>