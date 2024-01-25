<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import { ref, watch } from 'vue'
import Spinner from '@/Components/Spinner.vue';
import moment from 'moment/min/moment-with-locales';

const toast = useToast();
const busy_time = ref([]);
const free_rooms = ref([]);
const client_info = ref([]);
const showSpinner = ref(false);
const startTime = ref('');
const endTime = ref('');
const flash_time = ref([]);
const session_date = usePage().props.education_date

moment.locale('ru');

let formatter = new Intl.DateTimeFormat('ru', [{day: 'numeric'}, {month: 'numeric'}, {year: 'numeric'}])
let client_age = ''
let age_suffix = ''

const props = defineProps({
    users: Array,
    clients: Array,
    classes: Array,
    time_ranges: Array
})

// Add User Modal
const form = useForm({
    educationDate: session_date,
    time_range: -1,
    user_id: -1,
    client_id: -1,
    class_id: -1,
    room_id: -1,
    startTimeStamp: null,
    endTimeStamp: null
});

function checkEndTime() {
    flash_time.value = []
    form.errors.endTime =""
    form.startTimeStamp = moment(form.educationDate + ' ' + startTime.value).format('YYYY-MM-DD H:mm:ss')
    form.endTimeStamp = moment(form.educationDate + ' ' + endTime.value).format('YYYY-MM-DD H:mm:ss')
    if (form.startTimeStamp >= form.endTimeStamp) {
        form.errors.endTime = "Время окончания занятий должно быть больше времени начала."
    } else {
        busy_time.value.forEach(el => {
            if ((new Date(form.startTimeStamp) >= new Date(el.start_time) && new Date(form.startTimeStamp) < new Date(el.end_time))
             || (new Date(form.endTimeStamp) > new Date(el.start_time) && new Date(form.endTimeStamp) <= new Date(el.end_time))
             || (new Date(el.start_time) > new Date(form.startTimeStamp) && new Date(el.start_time) < new Date(form.endTimeStamp))) {
                flash_time.value.push(el.start_time + ' - ' + el.end_time)
            }
        });
        if (flash_time.value.length>0) {            
            form.errors.endTime = "Данный интервал пересекается с одним или несколькими из имеющихся в расписании"
        } else {
            getFreeRooms();
        }
    }
}

async function getClientInfo() {
    showSpinner.value = true;
    await axios.post('/api/get_client_info', {'client_id':form.client_id})
    .then((response) => {
        showSpinner.value=false;
        client_info.value=response.data.client_info
    })
    .catch((e) => {
        showSpinner.value = false;
    })
}

async function getBusyTime() {
    showSpinner.value = true;
    await axios.post('/api/get_busy_time', {'user_id':form.user_id,'educationDate':form.educationDate})
    .then((response) => {
        showSpinner.value = false;
        busy_time.value = response.data.busy_time
    })
    .catch((e) => {
        showSpinner.value = false;
    })
}

async function getFreeRooms() {
    showSpinner.value = true;
    await axios.post('/api/get_free_rooms', {'start_time_stamp':form.startTimeStamp, 'end_time_stamp': form.endTimeStamp, 'educationDate':form.educationDate})
    .then((response) => {
        showSpinner.value = false;
        free_rooms.value = response.data.free_rooms
    })
    .catch((e) => {
        showSpinner.value = false;
    })
}

const submit = () => {
    showSpinner.value = true;
    form.post(route("records.store"), {
        onSuccess: () => {
            showSpinner.value = false;
            toast.success("Запись в расписании успешно сохранена!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            showSpinner.value = false;
            toast.error("Ошибка при сохранении записи в расписании!", {
                timeout: 2000
            });
        },
    });
};

watch(client_info, (client_info, old_client_info) => {    
    client_age = String((new Date()).getFullYear() - (new Date(client_info[0].burndate).getFullYear()))
    age_suffix = (client_age.slice(-1) > 4) ? 'лет' : 'год(а)' 
})
</script>

<template>
    <Head title="Добавление новой записи в расписание занятий." />

    <AuthenticatedLayout>
        <template #header>
                <h2 class="text-gray-800 dark:text-gray-200 leading-tight">
                    Форма добавления записи в расписание
                </h2>
        </template>

        <div class="ml-3 mt-3 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div class="mb-5 pb-5 border-b border-gray-500">
                <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                    Форма добавления записи в расписание
                </h3>
                <span class="text-base font-normal text-gray-500">Поля помеченные * обязательны для заполнения.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="grid xl:grid-cols-2 2xl:grid-cols-2 gap-11">
                    <div> 
                        <div class="mb-4">
                            <InputLabel
                                for="educationDate"
                                value="* Дата занятия"
                            />

                            <TextInput
                                id="educationDate"
                                type="date"
                                class="mt-1 block w-full disabled:dark:bg-slate-800"
                                v-model="form.educationDate"
                                required
                                :disabled="form.user_id>0"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.educationDate"
                            />
                        </div>                       
                        <div class="mb-4">
                            <InputLabel for="client_id" value="* Клиент" />
                            <span class="text-sm text-gray-500">Если клиент отсутствует в списке необходимо добавить его в разделе 'Клиенты'</span>

                            <select
                                id="client_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:dark:bg-slate-800"
                                v-model="form.client_id"
                                required
                                @change="getClientInfo()"
                                :disabled="form.user_id>0"
                            >
                                <option :value="-1">
                                    Выберите клиента
                                </option>
                                <option v-for="client in clients" :key="client.id" :value="client.id">
                                    {{ client.fio }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.client_id"
                            />
                        </div>                        
                        <div class="mb-4" v-show="form.client_id>0&&form.educationDate !== ''">
                            <InputLabel for="user_id" value="* Специалист" />

                            <select
                                id="user_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:dark:bg-slate-800"
                                v-model="form.user_id"
                                required
                                @change="getBusyTime()"
                                :disabled="endTime !== ''"
                            >
                                <option :value="-1">
                                    Выберите специалиста
                                </option>
                                <option v-for="(user, index) in users" :key="index" :value="user.id">
                                    {{ user.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.user_id"
                            />
                        </div>
                        <div class="mb-4" v-show="form.user_id>0" >
                            <InputLabel for="time_range" value="* Время занятий (укажите время начала и окончания занятий)" />
                            <div v-if="form.user_id > 0 && busy_time.length > 0" class="mb-3 w-full">
                                <div class="text-xs"><span class="bg-rose-100 text-rose-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded">ВНИМАНИЕ:</span> Найдены занятия у <span class="underline">{{ users.find(user => user.id === form.user_id).name }}</span> на {{ moment(form.educationDate).format('DD.MM.YYYY') }} в </div>
                                <div class="flex flex-wrap">
                                    <div v-for="(busy, index) in busy_time" :key="index" 
                                        class="text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-indigo-500 dark:text-indigo-900 whitespace-nowrap mt-2" v-bind:class="(flash_time.find(el => el === (busy.start_time + ' - ' + busy.end_time))) ? 'bg-rose-100 text-rose-800':'bg-blue-100 text-blue-800'">
                                        {{ moment(busy.start_time).format('HH:mm') }}-{{ moment(busy.end_time).format('HH:mm') }}
                                    </div>
                                </div>
                            </div>
                            <div v-else-if="form.user_id > 0" class="mb-3">
                                <span class="text-xs">Справочно: Записи о занятиях в расписании у <span class="underline">{{ users.find(user => user.id === form.user_id).name }}</span> на {{ moment(form.educationDate).format('DD.MM.YYYY') }} не найдены. </span>
                            </div>
                            <div class="flex w-full">
                                <div class="mr-3">
                                    <TextInput
                                    id="startTime"
                                    type="time"
                                    class="mt-1 block w-full disabled:dark:bg-slate-800"
                                    v-model="startTime"
                                    required
                                    :disabled="endTime !== ''"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.time_range"
                                    />
                                </div>
                                <div class="my-auto">-</div>
                                <div class="ml-3">
                                    <TextInput
                                    id="endTime"
                                    type="time"
                                    class="mt-1 block w-full disabled:dark:bg-slate-800"
                                    v-model="endTime"
                                    @change="checkEndTime"
                                    required
                                    :disabled="form.room_id>0"
                                    />
                                </div>   
                            </div>                                                       

                            <InputError
                                        class="mt-2"
                                        :message="form.errors.endTime"
                                    />
                        </div>
                        <div class="mb-4" v-show="endTime !== '' && form.errors.endTime === ''" >
                            <InputLabel for="room_id" value="* Кабинет для занятий (в списке отображаются только свободные для этого специалиста на этот день кабинеты)" />

                            <select
                                id="time_range"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:dark:bg-slate-800"
                                v-model="form.room_id"
                                required
                                :disabled="form.class_id>0"
                            >
                                <option :value="-1">
                                    Выберите кабинет для занятий
                                </option>
                                <option v-for="room in free_rooms" :key="room.id" :value="room.id">
                                    {{ room.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.room_id"
                            />
                        </div>
                        <div class="mb-4" v-show="form.room_id>0" >
                            <InputLabel for="class_id" value="* Направление занятий" />

                            <select
                                id="class_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm disabled:dark:bg-slate-800"
                                v-model="form.class_id"
                                required
                            >
                                <option :value="-1">
                                    Выберите направление занятий
                                </option>
                                <option v-for="class_item in classes" :key="class_item.id" :value="class_item.id">
                                    {{ class_item.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.class_id"
                            />
                        </div>
                    </div>
                    <div v-if="typeof client_info !== 'undefined' && client_info.length > 0">
                        <h3 class="text-lg text-gray-900 dark:text-gray-300 mb-2">
                            Справочная информация о клиенте <strong class="text-indigo-500">{{ client_info[0].fio }}</strong>
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-gray-400">
                            <div class="font-semibold">Дата рождения:</div>
                            <div>{{ formatter.format(new Date(client_info[0].burndate)) }} ( Возраст - {{ client_age }} {{ age_suffix }} )</div>
                            <div class="font-semibold">Диагноз:</div>
                            <div>{{ client_info[0].diagnos }}</div>
                            <div class="font-semibold">Противопоказания:</div>
                            <div>{{ client_info[0].contras }}</div>
                            <div class="font-semibold">Мама:</div>
                            <div>{{ (client_info[0].mother !== '') ? client_info[0].mother : '-' }} / {{ (client_info[0].mother_phone !== '') ? client_info[0].mother_phone : '-' }}</div>
                            <div class="font-semibold">Папа:</div>
                            <div>{{ (client_info[0].father !== '') ? client_info[0].father : '-' }} / {{ (client_info[0].father_phone !== '') ? client_info[0].father_phone : '-' }}</div>
                            <div class="font-semibold">Адрес</div>
                            <div>{{ (client_info[0].adress !== '') ? client_info[0].adress : '-' }}</div>
                        </div>
                        <h3 class="mt-7 text-lg text-gray-900 dark:text-gray-300 mb-2">
                            Пожелания родителей по занятиям
                        </h3>
                        <Table v-if="client_info[0].wishes.length > 0">
                            <template #header>
                                <TableRow>
                                    <TableHeaderCell>Занятие</TableHeaderCell>
                                    <TableHeaderCell>Желаемое кол-во</TableHeaderCell>
                                    <TableHeaderCell>Желаемое время</TableHeaderCell>
                                </TableRow>
                            </template>
                            <template #default>
                                <TableRow v-for="wish in client_info[0].wishes" :key="wish.id" >
                                    <TableDataCell>{{ classes[wish.class_id].name }}</TableDataCell>
                                    <TableDataCell>{{ wish.prefer_amount_of_classes }}</TableDataCell>
                                    <TableDataCell>{{ time_ranges[wish.prefer_time_id].name }}</TableDataCell>
                                </TableRow>
                            </template>
                        </Table>
                        <span class="text-sm text-gray-500" v-else>Пожелания родителей не указаны!</span>
                    </div>
                </div>
                <div class="mt-6 pt-6 space-x-4 border-t border-gray-500">
                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.class_id<-1"
                    >
                        Добавить запись
                    </PrimaryButton>
                    <Link
                        :href="route('records.index')"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        Отмена
                    </Link>
                </div>
            </form>
        </div>
        <Spinner :showSpinner="showSpinner" />                
    </AuthenticatedLayout>
</template>
