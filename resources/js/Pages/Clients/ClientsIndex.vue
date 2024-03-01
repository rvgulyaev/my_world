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
import Pagination from "@/Components/Pagination.vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import Spinner from '@/Components/Spinner.vue';
import EmeraldButton from "@/Components/EmeraldButton.vue";
import { usePermissions } from "@/Composables/permissions";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import moment from 'moment/min/moment-with-locales';
import DropdownMenu from 'v-dropdown';

moment.locale('ru');
const { hasRole } = usePermissions();
const currentDate = moment().format('MM-DD')
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
    search_near_burndate: {
        type: Boolean,
        default: false
    },
    classes: {
        type: Object,
        required: true
    }
});

const searchForm = useForm({
    search_client_fio: props.search_client,
    search_near_burndate: props.search_near_burndate
})

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
        clientInfo.value = response.data.client_info;
        wishes_list = response.data.wishes;
        showInfoModal.value = true;
    })
    .catch((e) => {
        showSpinner.value = false;
        toast.error("Ошибка при получении информации о клиенте!", {
            timeout: 2000
        });
    })
}

function filterList() {
    showSpinner.value = true;
    searchForm.get(route('clients.index'), {
        onSuccess: () => {
            showSpinner.value=false;
        }
    })
}

function clearFilter() {
    searchForm.search_client_fio = ''
    searchForm.search_near_burndate = false
    filterList()
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
                    <div class="mb-4 flex items-center justify-between">
                        <PrimaryLink :href="route('clients.create')">
                            Добавить клиента
                        </PrimaryLink>
                        <a v-bind:href="route('clients.export')" class="whitespace-nowrap ml-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                            В Excel
                        </a>
                    </div>
                </div>
                <div class="mb-4 flex items-center justify-between">
                    <DropdownMenu class="ml-0 px-2 cursor-pointer flex items-center dark:text-gray-100" ref="dropdown">
                        <template #trigger>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>

                        ФИЛЬТРЫ
                        </template>                                                        
                        <ul class="dark:bg-gray-700 dark:border-gray-700 p-3">
                            <li>
                                <input class="mb-3 md:w-60 lg:w-80 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                type="text" 
                                placeholder="Поиск по ФИО..."
                                v-model="searchForm.search_client_fio"                       
                                autocomplete="props.search_client"
                                />
                            </li>
                            <li class="flex items-center">
                                <input
                                    type="checkbox"
                                    v-model="searchForm.search_near_burndate"
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                />
                                <InputLabel class="ml-1 dark:text-gray-400"> - Кого сегодня поздравить с ДР</InputLabel>
                            </li>
                            <li class="flex justify-between items-center mt-3 pt-3 border-t border-gray-500">
                                <PrimaryButton @click="filterList">
                                    Применить
                                </PrimaryButton>
                                <SecondaryButton @click="clearFilter">
                                    Сброс
                                </SecondaryButton>
                            </li>
                        </ul>
                    </DropdownMenu>                       
                       <div class="flex-shrink-0">
                            <Link :href="route('clients.trashed')" class="uppercase hover:underline hover:decoration-solid hover:decoration-slate-500 dark:text-slate-300" v-if="hasRole('admin')">Перейти в корзину</Link>
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
                                                <Link :href="route('clients.edit',client)"
                                                    class="ml-3 text-xm leading-5 font-bold text-indigo-600 dark:text-indigo-500 hover:text-indigo-300"
                                                >{{ client.fio }}
                                                </Link>
                                            </TableDataCell>
                                            <TableDataCell><div class="flex items-center justify-between"><img class="mr-2" src="/images/balloons.png" v-if="moment(client.burndate).format('MM-DD') == currentDate" />{{ moment(client.burndate).format("DD.MM.YYYY") }}</div></TableDataCell>
                                            <TableDataCell style="white-space:normal">{{ client.contras }}</TableDataCell>
                                            <TableDataCell>
                                                <template class="flex flex-wrap w-80">
                                                    <span v-for="wish in client.wishes" :key="wish.id"
                                                    class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded  whitespace-normal dark:bg-indigo-300 dark:text-indigo-700 mt-2"
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
                                        <strong>Напр:</strong> {{ wish.class }} || <strong>Кол-во:</strong> {{ wish.prefer_amount_of_classes }} || <strong>Дни:</strong> {{ wish.prefer_day }} || <strong>Время:</strong> {{ wish.prefer_time }}
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
