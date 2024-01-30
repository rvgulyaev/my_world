<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref, reactive } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PinkButton from "@/Components/PinkButton.vue";
import SearchInput from "@/Components/SearchInput.vue";
import Pagination from "@/Components/Pagination.vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import Spinner from '@/Components/Spinner.vue';
import EmeraldButton from "@/Components/EmeraldButton.vue";

const toast = useToast();
const showSpinner = ref(false);
const props = defineProps({
    clients: {
        type: Object,
        required: true,
    },
    search_client: {
        type: String,
        default: ''
    },
    classes: {
        type: Object,
        required: true
    }
});

const clientInfo = ref([]);
let showInfoModal = ref(false);
let wishes_list = reactive([]);

// Delete User Modal
const deleteForm = useForm({
    client_id: null
});
const showConfirmDeleteModal = ref(false);
const confirmDelete = (client_id) => {
    showConfirmDeleteModal.value = true;
    deleteForm.client_id = client_id;
};
const closeModal = () => {
    showConfirmDeleteModal.value = false;
    showInfoModal.value = false;
};
const deleteClient = () => {
    deleteForm.delete(route("clients.destroy", deleteForm.client_id), {
        onSuccess: () => {
            closeModal();
            toast.success("Клиент успешно удален в корзину!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            closeModal();
            toast.error("Ошибка при удалении клиента!", {
                timeout: 2000
            });
        },
    });
};

async function getClientInfo(client_id) {
    showSpinner.value = true;
    await axios.post('/api/get_client_info', {'client_id':client_id})
    .then((response) => {
        showSpinner.value=false;
        clientInfo.value=response.data.client_info[0];
        wishes_list = clientInfo.value.wishes;
        console.log(wishes_list)
        wishes_list.forEach(el => {
            el.class_name = props.classes[props.classes.findIndex((obj) => obj.id === el.class_id)].name
        });
        showInfoModal.value = true;
    })
    .catch((e) => {
        console.log(e)
        showSpinner.value = false;
        toast.error("Ошибка при получении информации о клиенте!", {
            timeout: 2000
        });
    })
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 leading-tight">
                Список клиентов
            </h2>
        </template>

        <div class="mb-4">
            <div
                class="bg-white dark:bg-gray-700 shadow rounded-lg p-4 sm:p-6 xl:p-8"
            >
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                            Список клиентов
                        </h3>
                        <span class="text-base font-normal text-gray-500"
                            >Раздел предназначен для ведения списка
                            клиентов.</span
                        >
                    </div>
                    <div class="flex-shrink-0">
                        <PrimaryLink :href="route('clients.create')">
                            Добавить клиента
                        </PrimaryLink>
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-between">
                       <SearchInput :search_field="'search_client_fio'" :search="props.search_client" :route_link="'clients.index'" :pholder="'Поиск по ФИО...'"/> 
                       <div class="flex-shrink-0">
                            <Link :href="route('clients.trashed')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300">Перейти в корзину</Link>
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
                                            <TableHeaderCell>Противопоказания</TableHeaderCell>
                                            <TableHeaderCell>Пожелания родителей</TableHeaderCell>
                                            <TableHeaderCell>Создано</TableHeaderCell>
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
                                            <TableDataCell>{{ client.contras }}</TableDataCell>
                                            <TableDataCell>
                                                <template class="flex flex-wrap w-80">
                                                    <span v-for="wish in client.wishes" :key="wish.id"
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded  whitespace-normal dark:bg-indigo-500 dark:text-indigo-900 mt-2"
                                                    >
                                                    <strong>Направление:</strong> {{ wish.class }} &bull; <strong>Кол-во:</strong> {{ wish.prefer_amount_of_classes }} &bull; <strong>Дни:</strong> {{ wish.prefer_day }} &bull; <strong>Время:</strong> {{ wish.prefer_time }}
                                                    </span>
                                                </template>                                                
                                            </TableDataCell>
                                            <TableDataCell>{{ client.created_by }}<br /><span>{{ client.created_at }}</span></TableDataCell>                                           
                                            <TableDataCell>
                                                <EmeraldButton @click="getClientInfo(client.id)">
                                                    Инфо
                                                </EmeraldButton>
                                                <PinkButton @click="confirmDelete(client.id)">
                                                    Удалить
                                                </PinkButton>
                                            </TableDataCell>
                                        </TableRow>
                                    </template>
                                </Table>
                                <pagination class="mt-6" :links="clients.meta.links" />
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
                        Подтвердите удаление клиента!
                    </h2>
                </div>
                <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                    <DangerButton @click="deleteClient">Удалить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>

        <Modal :show="showInfoModal" @close="closeModal" :maxWidth="'xl'">
            <div class="p-6">
                <div>
                    <div class="mb-1">
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">ФИО:</span>
                                <div>{{ clientInfo.fio }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Дата рождения:</span>
                                <div>{{ clientInfo.burndate }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Диагноз:</span>
                                <div>{{ clientInfo.diagnos }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Противопоказания:</span>
                                <div>{{ clientInfo.contras }}</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Мама:</span>
                                <div>{{ clientInfo.mother }} (тел - {{ clientInfo.mother_phone }})</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Папа:</span>
                                <div>{{ clientInfo.father }} (тел - {{ clientInfo.father_phone }})</div>
                            </div>
                            <div class="pb-3 border-b border-slate-300 mb-3">
                                <span class="uppercase font-bold">Адрес проживания:</span>
                                <div>{{ clientInfo.adress }}</div>
                            </div>
                            <div>
                                <span class="uppercase font-bold">Пожелания по занятиям:</span>
                                <div>
                                    <span v-for="(wish, index) in wishes_list" :key="index" 
                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-500 dark:text-indigo-900">
                                        <strong>Напр:</strong> {{ wish.class_name }} || <strong>Кол-во:</strong> {{ wish.prefer_amount_of_classes }} || <strong>Дни:</strong> {{ wish.prefer_day }} || <strong>Время:</strong> {{ wish.prefer_time }}
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
        <Spinner :showSpinner="showSpinner" />  
    </AuthenticatedLayout>
</template>
