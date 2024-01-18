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
    clients: {
        type: Array,
        required: true,
    },
    search_client: {
        type: String,
        default: ''
    }
})

// Delete User Modal
const deleteForm = useForm({
    client_id: -1
})
const restoreForm = useForm({
    client_id: -1
})
const showConfirmDeleteClientModal = ref(false)
const confirmDeleteClient = (id) => {
    deleteForm.client_id = id;
    showConfirmDeleteClientModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteClientModal.value = false;
}
const deleteClient = () => {
    deleteForm.post(route('clients.terminate', deleteForm.client_id), {
        onSuccess: () => { 
            closeModal(); 
            toast.success("Клиент успешно удален!", {
                timeout: 2000
            });
        },
        onError: () => {
            closeModal(); 
            toast.error("Ошибка при удалении клиента!", {
                timeout: 2000
            });
        }
    })
}

const restoreClient = (client) => {
    restoreForm.client_id = client.id
    restoreForm.post(route('clients.restore', restoreForm.client_id), {
        onSuccess: () => { 
            toast.success("Клиент успешно восстановлен!", {
                timeout: 2000
            });
        },
        onError: () => {
            toast.error("Ошибка при восстановлении клиента!", {
                timeout: 2000
            });
        }
    })
}
</script>

<template>
  <Head title="Корзина списка клиентов" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Корзина списка клиентов</h2>
    </template>

           <div class="mb-4">
              <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                 <div class="mb-4 flex items-center justify-between">
                    <div>
                       <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Корзина списка клиентов</h3>
                       <span class="text-base font-normal text-gray-500">Для восстановления клиента нажмите кнопку "Восстановить". Для окончательного удаления клиента нажмите кнопку "Удалить".</span>
                    </div>
                 </div>                 
                <div class="mb-4 flex items-center justify-between">
                       <SearchInput :search_field="'search_client'" :search="props.search_client" :route_link="'clients.index'" :pholder="'Поиск по задаче...'"/> 
                       <div class="flex-shrink-0">
                            <Link :href="route('clients.index')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в список клиентов</Link>
                        </div>
                </div>
                 <div class="flex flex-col mt-8">
                    <div class="overflow-x-auto rounded-lg">
                       <div class="align-middle inline-block min-w-full">
                          <div class="shadow overflow-hidden pb-3">
                            <div v-if="clients.data.length > 0">
                             <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Id</TableHeaderCell>
                                        <TableHeaderCell>ФИО</TableHeaderCell>
                                        <TableHeaderCell>Дата рождения</TableHeaderCell>
                                        <TableHeaderCell>Диагноз</TableHeaderCell>
                                        <TableHeaderCell>Противопоказания</TableHeaderCell>
                                        <TableHeaderCell>Создано</TableHeaderCell>
                                        <TableHeaderCell>Обновлено</TableHeaderCell>
                                        <TableHeaderCell>Действия</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="client in clients.data" :key="client.id">
                                            <TableDataCell>{{ client.id }}</TableDataCell>
                                            <TableDataCell>
                                                <Link :href="route('clients.edit',client.id)"
                                                    class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300"
                                                >{{ client.fio }}
                                                </Link>
                                            </TableDataCell>
                                            <TableDataCell>{{ client.burndate }}</TableDataCell>
                                            <TableDataCell>{{ client.diagnos }}</TableDataCell>
                                            <TableDataCell>{{ client.contras }}</TableDataCell>
                                            <TableDataCell>{{ client.created_by }}<br /><span>{{ client.created_at }}</span></TableDataCell>
                                            <TableDataCell>{{ client.updated_by }}<br /><span>{{ client.updated_at }}</span></TableDataCell>
                                            <TableDataCell v-if="hasRole('admin')">
                                                <EmeraldButton @click="restoreClient(client)">Восстановить</EmeraldButton>  
                                                <PinkButton @click="confirmDeleteClient(client.id)">Удалить</PinkButton> 
                                            </TableDataCell>
                                        </TableRow>
                                </template>
                             </Table>
                             <pagination class="mt-6" :links="clients.meta.links" />
                             </div>
                             <span v-else class="dark:text-slate-300">Корзина пуста.</span>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
           <Modal :show="showConfirmDeleteClientModal" @close="closeModal" :maxWidth="'sm'">
                <div class="p-6 dark:bg-gray-600">
                    <div class="flex items-center justify-center">
                        <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-300">
                            Подтвердите удаление клиента! Клиент будет удален окончательно и безповоротно!
                        </h2>
                    </div>
                    <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                        <DangerButton @click="deleteClient">Удалить</DangerButton>
                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                    </div>
                </div>
            </Modal>
</AuthenticatedLayout>
</template>