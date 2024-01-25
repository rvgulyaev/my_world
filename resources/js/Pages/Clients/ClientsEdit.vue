<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref, reactive, onBeforeMount } from "vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import { useToast } from "vue-toastification";
import PinkButton from "@/Components/PinkButton.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const toast = useToast();
const showAddWishModal = ref(false)
const back_url = usePage().props.back_url
const props  = defineProps({
    client: {
        type: Object,
        required: true
    },
    classes: Array,
})

let wishes_list = reactive([]);
// Add User Modal
const form = useForm({
    fio: props.client?.fio,
    burndate: props.client?.burndate,
    diagnos: props.client?.diagnos,
    contras: props.client?.contras,
    mother: props.client?.mother,
    mother_phone: props.client?.mother_phone,
    father: props.client?.father,
    father_phone: props.client?.father_phone,
    adress: props.client?.adress,
    wishes: props.client?.wishes,
});

const checkedClass = ref(null);
const classesAmount = ref('');
const preferDay = ref('');
const preferTime = ref('');

function showModal() {
    showAddWishModal.value = true
}

function closeModal() {
    showAddWishModal.value = false
}

// Добавить пожелание
function addWish() {
    wishes_list.push({
        id: new Date(),
        class_id: checkedClass.value,
        class_name: props.classes.filter(
            (item) => item.id === checkedClass.value
        )[0].name,
        prefer_amount_of_classes: classesAmount.value,
        prefer_day: preferDay.value,
        prefer_time: preferTime.value
    });
    form.wishes.push({
        class_id: checkedClass.value,
        prefer_amount_of_classes: classesAmount.value,
        prefer_day: preferDay.value,
        prefer_time: preferTime.value
    });
    checkedClass.value = null;
    classesAmount.value = '';
    preferDay.value = ''
    preferTime.value = '';
    closeModal();
}

// Удалить пожелание
function delWish(wish) {
    const objIndex = wishes_list.findIndex((obj) => obj.id === wish.id);
    if (objIndex > -1) {
        wishes_list.splice(objIndex, 1);
        form.wishes.splice(objIndex, 1);
    }
}

const submit = async () => {
    form.put(route('clients.update', props.client), {
        onSuccess: () => {
            toast.success("Данные о клиенте успешно сохранены!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении данных о клиенте!", {
                timeout: 2000
            });
        },
    });
};
onBeforeMount(()=>{
    wishes_list = props.client?.wishes
    wishes_list.forEach(el => {
        el.class_name = props.classes[props.classes.findIndex((obj) => obj.id === el.class_id)].name
    });
})
</script>

<template>
    <Head title="Редактирование пользователя" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Форма редактирования пользователя</h2>
        </template>

        <div class="ml-3 mt-3 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Форма редактирования пользователя</h3>
                <span class="text-base font-normal text-gray-500">Заполните все поля и нажмите кнопку "Изменить" для сохранения данных.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="mt-6">
                        <div class="grid xl:grid-cols-2 2xl:grid-cols-2 gap-4">
                        <div>
                            <div class="mb-4">
                                <InputLabel for="fio" value="* ФИО клиента" />

                                <TextInput
                                    id="fio"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.fio"
                                    required
                                    autofocus
                                    autocomplete="fio"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.fio"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel
                                    for="burndate"
                                    value="* Дата рождения"
                                />

                                <TextInput
                                    id="burndate"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="form.burndate"
                                    required
                                    autocomplete="burndate"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.burndate"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="diagnos" value="* Диагноз" />

                                <TextInput
                                    id="diagnos"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.diagnos"
                                    required
                                    autocomplete="diagnos"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.diagnos"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel
                                    for="contras"
                                    value="* Противопоказания"
                                />

                                <TextInput
                                    id="contras"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.contras"
                                    required
                                    autocomplete="new-contras"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.contras"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="mother" value="Мама клиента" />

                                <TextInput
                                    id="mother"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.mother"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.mother"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel
                                    for="mother_phone"
                                    value="Телефон мамы"
                                />

                                <TextInput
                                    id="mother_phone"
                                    type="text"
                                    v-mask="'+7(###)-###-##-##'"
                                    class="mt-1 block w-full"
                                    v-model="form.mother_phone"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.mother_phone"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="father" value="Папа клиента" />

                                <TextInput
                                    id="father"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.father"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.father"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel
                                    for="father_phone"
                                    value="Телефон папы"
                                />

                                <TextInput
                                    id="father_phone"
                                    type="text"
                                    v-mask="'+7(###)-###-##-##'"
                                    class="mt-1 block w-full"
                                    v-model="form.father_phone"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.father_phone"
                                />
                            </div>

                            <div class="mb-4">
                                <InputLabel for="adress" value="Адрес проживания" />

                                <TextInput
                                    id="adress"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.adress"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.adress"
                                />
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">                                
                            
                            <div class="flex mt-2 mb-3 pb-3 border-b border-gray-300 dark:border-gray-500">
                                <div class="w-full mr-5">                                    
                                <InputLabel
                                    for="wishes"
                                    value="Пожелания по занятиям"
                                />
                                </div> 
                                <div>
                                    <button
                                        type="button"
                                        class="block w-full bg-blue-100 text-blue-800 mt-1 px-4 py-2.5 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest dark:bg-indigo-500 dark:text-indigo-900 dark:hover:bg-indigo-400 hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                        @click="showModal"
                                    >
                                        Добавить
                                    </button>
                                </div>
                            </div>

                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Занятие</TableHeaderCell>
                                        <TableHeaderCell>Желаемое кол-во</TableHeaderCell>
                                        <TableHeaderCell>Желаемый день(и)</TableHeaderCell>
                                        <TableHeaderCell>Желаемое время</TableHeaderCell>
                                        <TableHeaderCell>Действия</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="wish in wishes_list" :key="wish.id">
                                        <TableDataCell>{{ wish.class_name }}</TableDataCell>
                                        <TableDataCell>{{ wish.prefer_amount_of_classes }}</TableDataCell>
                                        <TableDataCell>{{ wish.prefer_day }}</TableDataCell>
                                        <TableDataCell>{{ wish.prefer_time }}</TableDataCell>
                                        <TableDataCell>
                                            <PinkButton @click="delWish(wish)">Удалить</PinkButton>
                                        </TableDataCell>
                                    </TableRow>
                                </template>
                            </Table>
                            </div>
                        </div>
                        </div>
                </div>                
                <div class="mt-6 pt-6 space-x-4 border-t border-gray-500">
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Обновить данные о клиенте
                        </PrimaryButton>
                        <Link
                            :href="back_url"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            Отмена
                        </Link>
                </div>            
            </form>
        </div>
        <Modal :show="showAddWishModal" @close="closeModal" :maxWidth="'sm'">
            <div class="p-6 dark:bg-gray-700">
                    <div class="mb-4">
                        <InputLabel
                            for="checkedClass"
                            value="* Направление занятий"
                        />

                        <select
                                id="checkedClass"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                v-model="checkedClass"
                            >
                                <option :value="null" disabled>
                                    Выберите направление занятий
                                </option>
                                <option
                                    v-for="(
                                        classItem, index
                                    ) in classes"
                                    :key="index"
                                    :value="classItem.id"
                                >
                                    {{ classItem.name }}
                                </option>
                            </select>
                    </div>
                    <div class="mb-4">
                        <InputLabel
                            for="classesAmount"
                            value="* Количество занятий"
                        />
                        <TextInput
                            id="classesAmount"
                            type="number"
                            placeholder="Укажите желаемое количество занятий"
                            class="mt-1 block w-full"
                            v-model="classesAmount"
                            min="1"
                        />
                    </div>
                    <div class="mb-4">
                        <InputLabel
                            for="preferDay"
                            value="* Дни недели"
                        />
                        <TextInput
                            id="preferDay"
                            type="text"
                            placeholder="Укажите дни недели"
                            class="mt-1 block w-full"
                            v-model="preferDay"
                            autocomplite="preferDay"
                        />
                    </div>
                    <div class="mb-4">
                        <InputLabel
                            for="preferTime"
                            value="* Время занятий"
                        />
                        <span class="text-xs">Маска ##:## - ##:## (с обязательным 0 до 10)</span>
                        <TextInput
                            id="preferTime"
                            type="text"
                            placeholder="Укажите желаемое время занятий"
                            class="mt-1 block w-full"
                            v-mask="'##:## - ##:##'"
                            v-model="preferTime"
                        />
                    </div>
                <div class="mt-6 border-t pt-5 border-gray-500 space-x-2 flex items-center justify-center">
                    <DangerButton @click="addWish">Добавить</DangerButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
