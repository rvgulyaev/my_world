<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import { reactive, ref } from "vue";
import { useToast } from "vue-toastification";

const toast = useToast();

// Add User Modal
const form = useForm({
    task: "",
    executeDate: "",
});

const submit = () => {
    form.post(route("tasks.store"), {
        onSuccess: () => {
            toast.success("Данные о задаче успешно сохранены!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении данных о задаче!", {
                timeout: 2000
            });
        },
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">
                Форма добавления задачи
            </h2>
        </template>

        <div class="ml-3 mt-3 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">
                    Форма добавления задачи
                </h3>
                <span class="text-base font-normal text-gray-500">Поля помеченные * обязательны для заполнения.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="grid xl:grid-cols-2 2xl:grid-cols-2 gap-4">
                    <div>
                        <div class="mb-4">
                            <InputLabel for="task" value="* Задача" />

                            <textarea id="task" rows="4" 
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"                            
                            v-model="form.task"
                            maxlength="255"
                            required
                            autofocus
                            ></textarea>

                            <InputError
                                class="mt-2"
                                :message="form.errors.task"
                            />
                        </div>

                        <div class="mb-4">
                            <InputLabel
                                for="executeDate"
                                value="* Срок исполнения задачи"
                            />

                            <TextInput
                                id="executeDate"
                                type="date"
                                class="mt-1 block w-full"
                                v-model="form.executeDate"
                                required
                                autocomplete="executeDate"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.executeDate"
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
                        Добавить задачу
                    </PrimaryButton>
                    <Link
                        :href="route('tasks.index')"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        Отмена
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
