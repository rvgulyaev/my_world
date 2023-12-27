<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';
import { reactive, ref } from 'vue';


const props  = defineProps({
    classes: Array,
    timeRanges:Array
})

let wishes_list = reactive([]);

const checkedClass = ref(null);
const classesAmount = ref(null);
const preferTime = ref(null);

// Добавить пожелание
function addWish () {
    wishes_list.push(
        {
            id:new Date(),
            class_id:checkedClass.value,
            class_name:props.classes.filter((item) => item.id === checkedClass.value)[0].name,
            prefer_amount_of_classes:classesAmount.value,
            prefer_time_id:preferTime.value,
            prefer_time:props.timeRanges.filter((item) => item.id === preferTime.value)[0].name
        }
    )
    form.wishes.push({
        'class_id':checkedClass.value,
        'prefer_amount_of_classes':classesAmount.value,
        'prefer_time':preferTime.value
    })
    checkedClass.value = null
    classesAmount.value = null
    preferTime.value = null
}

// Удалить пожелание
function delWish(wish) {
    wishes_list = wishes_list.filter((item) => item.id !== wish.id)
    form.wishes = form.wishes.filter((item) => item.id !== wish.id)
}

// Add User Modal
const form = useForm({
    fio: '',
    burndate: '',
    diagnos: '',
    contras: '',
    mother: '',
    mother_phone: '',
    father: '',
    father_phone: '',
    adress: '',
    wishes: [],
});

const submit = () => {
    form.post(route('clients.store'), {
        onSuccess: () => {
            this.$notify({
                            title: "Успех!!!",
                            text: "Данные о клиенте успешно сохранены!",
                            type: "success"
                        });
        },
        onError: (e) => {
            this.$notify({
                            title: "Ошибка!!!",
                            text: "Ошибка при сохранении данных о клиенте!",
                            type: "error"
                        });
        }
    });
}

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Форма добавления клиента</h2>
        </template>

        <div class="ml-3 mt-3 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Форма добавления клиента</h3>
                <span class="text-base font-normal text-gray-500">Поля помеченные * обязательны для заполнения.</span>
            </div>
            <form @submit.prevent="submit">
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

                            <InputError class="mt-2" :message="form.errors.fio" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="burndate" value="* Дата рождения" />

                            <TextInput
                                id="burndate"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="form.burndate"
                                required
                                autofocus
                                autocomplete="burndate"
                            />

                            <InputError class="mt-2" :message="form.errors.burndate" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="diagnos" value="* Диагноз" />

                            <TextInput
                                id="diagnos"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.diagnos"
                                required
                                autofocus
                                autocomplete="diagnos"
                            />

                            <InputError class="mt-2" :message="form.errors.diagnos" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="contras" value="* Противопоказания" />

                            <TextInput
                                id="contras"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.contras"
                                required
                                autocomplete="new-contras"
                            />

                            <InputError class="mt-2" :message="form.errors.contras" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="mother" value="Мама клиента" />

                            <TextInput
                                id="mother"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.mother"
                            />

                            <InputError class="mt-2" :message="form.errors.mother" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="mother_phone" value="Телефон мамы" />

                            <TextInput
                                id="mother_phone"
                                type="text"
                                v-mask ="'+7(###)-###-##-##'"
                                class="mt-1 block w-full"
                                v-model="form.mother_phone"
                            />

                            <InputError class="mt-2" :message="form.errors.mother_phone" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="father" value="Папа клиента" />

                            <TextInput
                                id="father"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.father"
                            />

                            <InputError class="mt-2" :message="form.errors.father" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="father_phone" value="Телефон папы" />

                            <TextInput
                                id="father_phone"
                                type="text"
                                v-mask ="'+7(###)-###-##-##'"
                                class="mt-1 block w-full"
                                v-model="form.father_phone"
                            />

                            <InputError class="mt-2" :message="form.errors.father_phone" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="adress" value="Адрес проживания" />

                            <TextInput
                                id="adress"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.adress"
                            />

                            <InputError class="mt-2" :message="form.errors.adress" />
                        </div>
                    </div>
                    <div>
                        <div class="mb-4">
                            <InputLabel for="wishes" value="Пожелания по занятиям" />
                            <div class="grid xl:grid-cols-4 2xl:grid-cols-4 gap-4 mt-3 mb-3">
                                <div>
                                    <InputLabel for="checkedClass" value="Список занятий" />
                                        <select class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" v-model="checkedClass">
                                            <option
                                                    v-for="(classItem, index) in classes"
                                                    :key="index"
                                                    :value="classItem.id"
                                            >
                                                {{classItem.name}}
                                            </option>
                                        </select>
                                </div>
                                <div>
                                    <InputLabel for="classesAmount" value="Кол-во" />
                                    <TextInput
                                            id="classesAmount"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="classesAmount"
                                            required
                                            autofocus
                                        />
                                </div>
                                <div>
                                    <InputLabel for="checkedClass" value="предпочитаемое время" />
                                        <select class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        v-model="preferTime">
                                            <option
                                                    v-for="(time, index) in timeRanges"
                                                    :key="index"
                                                    :value="time.id"
                                            >
                                                {{time.name}}
                                            </option>
                                        </select>
                                </div>
                                <div class="relative h-15">
                                    <button type="button" class="absolute inset-x-0 bottom-0 bg-blue-100 text-blue-800 text-lg me-2 p-2 rounded dark:bg-indigo-500 dark:text-indigo-900 dark:hover:bg-indigo-400 hover:bg-blue-300 "
                                    @click="addWish">Добавить</button>
                                </div>
                            </div>

                            <Table>
                                <template #header>
                                    <TableRow>
                                        <TableHeaderCell>Занятие</TableHeaderCell>
                                        <TableHeaderCell>Желаемое кол-во</TableHeaderCell>
                                        <TableHeaderCell>Желаемое время</TableHeaderCell>
                                        <TableHeaderCell>Действия</TableHeaderCell>
                                    </TableRow>
                                </template>
                                <template #default>
                                    <TableRow v-for="wish in wishes_list" :key="wish.id">
                                        <TableDataCell>{{ wish.class_name }}</TableDataCell>
                                        <TableDataCell>{{ wish.prefer_amount_of_classes }}</TableDataCell>
                                        <TableDataCell>{{ wish.prefer_time }}</TableDataCell>
                                        <TableDataCell>
                                            <button @click="delWish(wish)" type="button"
                                            class="bg-red-100 text-red-800 text-xs font-medium me-2 px-3.5 py-1.5 rounded dark:bg-pink-900 dark:text-pink-500 hover:bg-pink-800 "
                                            >
                                            Удалить
                                            </button>
                                        </TableDataCell>
                                    </TableRow>
                                </template>
                             </Table>

                            <InputError class="mt-2" :message="form.errors.wishes" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 space-x-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Добавить
                    </PrimaryButton>
                    <Link :href="route('users.index')"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Отмена
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
