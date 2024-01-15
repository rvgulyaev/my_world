<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useToast } from "vue-toastification";

const toast = useToast();

// Add User Modal
const form = useForm({
    name: "",
});

const submit = () => {
    form.post(route("admin.time-ranges.store"), {
        onSuccess: () => {
            toast.success("Данные о временном диапазоне успешно сохранены!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении данных о временном диапазоне!", {
                timeout: 2000
            });
        },
    });
};
</script>

<template>
    <Head title="Форма добавления временного диапазона" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">
                Форма добавления временного диапазона
            </h2>
        </template>

        <div class="max-w-lg ml-3 mt-3 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">
                    Форма добавления временного диапазона
                </h3>
                <span class="text-base font-normal text-gray-500">Поля помеченные * обязательны для заполнения.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <div>
                        <div class="mb-4">
                            <InputLabel for="name" value="* Наименование временного диапазона" />

                            <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
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
                        Добавить временной диапазон
                    </PrimaryButton>
                    <Link
                        :href="route('admin.time-ranges.index')"
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                    >
                        Отмена
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
