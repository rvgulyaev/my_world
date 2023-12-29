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
import PrimaryLink from '@/Components/PrimaryLink.vue';
import PinkButton from '@/Components/PinkButton.vue';

const props  = defineProps({
    clients: {
        type: Object,
        required: true,
    },
})

// Delete User Modal
const deleteForm = useForm({})
const showConfirmDeleteModal = ref(false)
const confirmDelete = () => {
    showConfirmDeleteModal.value = true;
}
const closeModal = () => {
    showConfirmDeleteModal.value = false;
    showAddUserFormModal.value = false;
}
const deleteClient = (id) => {
    deleteForm.delete(route('clients.destroy', id), {
        onSuccess: () => closeModal()
    })
}
</script>

<template>
  <Head title="Dashboard" />

<AuthenticatedLayout>
    <template #header>
        <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Список клиентов</h2>
    </template>

           <div class="mb-4">
              <div class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                 <div class="mb-4 flex items-center justify-between">
                    <div>
                       <h3 class="text-xl font-bold text-gray-900 mb-2">Список клиентов</h3>
                       <span class="text-base font-normal text-gray-500">Раздел предназначен для ведения списка клиентов.</span>
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('clients.create')">
                        Добавить клиента
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
                                    <TableRow v-for="client in clients" :key="client.id">
                                        <TableDataCell>{{ client.id }}</TableDataCell>
                                        <TableDataCell>
                                            <Link :href="route('clients.edit', client.id)" class="text-blue-400 font-semibold hover:underline">{{ client.fio }}</Link>    
                                        </TableDataCell>
                                        <TableDataCell>{{ client.burndate }}</TableDataCell>
                                        <TableDataCell>{{ client.diagnos }}</TableDataCell>
                                        <TableDataCell>{{ client.contras }}</TableDataCell>
                                        <TableDataCell>{{ client.created_by }}<br><span>{{ client.created_at }}</span></TableDataCell>
                                        <TableDataCell>{{ client.updated_by }}<br><span>{{ client.updated_at }}</span></TableDataCell>
                                        <TableDataCell>
                                            <PinkButton @click="confirmDelete">Удалить</PinkButton>
                                            <Modal :show="showConfirmDeleteModal" @close="closeModal" :maxWidth="'sm'">
                                                <div class="p-6">
                                                    <h2 class="text-lg font-semibold text-slate-800">
                                                        Подтвердите удаление клиента!
                                                    </h2>
                                                    <div class="mt-6 flex space-x-4">
                                                        <DangerButton @click="deleteClient(client.id)">Удалить</DangerButton>
                                                        <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                                                    </div>
                                                </div>
                                            </Modal>
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
</AuthenticatedLayout>
</template>