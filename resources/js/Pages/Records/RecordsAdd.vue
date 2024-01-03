<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useToast } from "vue-toastification";
import axios from "axios";
import { ref } from 'vue'

const toast = useToast();
const free_time_ranges = ref([]);
const free_rooms = ref([]);

const props = defineProps({
    users: Array
})

// Add User Modal
const form = useForm({
    educationDate: null,
    time_range: -1,
    user_id: null,
    client_id: 0,
    class_id: 0,
    room_id: 0,
});

async function getFreeTimeRanges() {
    await axios.post('/api/get_free_time_ranges', {'user_id':form.user_id,'educationDate':form.educationDate})
    .then((response) => {
        free_time_ranges.value = response.data.free_time_ranges
    })
    .catch((e) => {
        console.log(e)
    })
}

async function getFreeRooms() {
    await axios.post('/api/get_free_rooms', {'time_range':form.time_range,'educationDate':form.educationDate})
    .then((response) => {
        free_rooms.value = response.data.free_rooms
    })
    .catch((e) => {
        console.log(e)
    })
}

const submit = () => {
    form.post(route("records.store"), {
        onSuccess: () => {
            toast.success("Запись в расписании успешно сохранена!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении записи в расписании!", {
                timeout: 2000
            });
        },
    });
};
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
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    Форма добавления записи в расписание
                </h3>
                <span class="text-base font-normal text-gray-500">Поля помеченные * обязательны для заполнения.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="grid xl:grid-cols-2 2xl:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-4">
                            <InputLabel
                                for="educationDate"
                                value="* Дата занятия"
                            />

                            <TextInput
                                id="educationDate"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="form.educationDate"
                                required
                                autocomplete="educationDate"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.educationDate"
                            />
                        </div>
                        <div class="mb-4" v-show="form.educationDate">
                            <InputLabel for="user_id" value="* Специалист" />

                            <select
                                id="user_id"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                v-model="form.user_id"
                                @change="getFreeTimeRanges()"
                            >
                                <option :value="null" disabled>
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
                        <div class="mb-4" v-show="form.user_id" >
                            <InputLabel for="time_range" value="* Время занятий (в списке отображаются только свободные для этого специалиста на этот день временные диапазоны)" />

                            <select
                                id="time_range"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                v-model="form.time_range"
                                @change="getFreeRooms()"
                            >
                                <option :value="-1" disabled>
                                    Выберите время занятий
                                </option>
                                <option v-for="time in free_time_ranges" :key="time.id" :value="time.id">
                                    {{ time.name }}
                                </option>
                            </select>

                            <InputError
                                class="mt-2"
                                :message="form.errors.time_range"
                            />
                        </div>
                        <div class="mb-4" v-show="form.time_range>0" >
                            <InputLabel for="room_id" value="* Кабинет для занятий (в списке отображаются только свободные для этого специалиста на этот день кабинеты)" />

                            <select
                                id="time_range"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                v-model="form.room_id"
                            >
                                <option :value="-1" disabled>
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
                    </div>
                </div>
                <div class="mt-6 pt-6 space-x-4 border-t border-gray-500">
                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
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
    </AuthenticatedLayout>
</template>
