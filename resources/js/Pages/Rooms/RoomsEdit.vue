<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import { useToast } from "vue-toastification";

const toast = useToast();
const props  = defineProps({
    room: {
        type: Object,
        required: true
    },
})

// Add User Modal
const form = useForm({
    name: props.room?.name,
});
const submit = async () => {
    form.put(route('admin.rooms.update', props.room), {
        onSuccess: () => {
            toast.success("Данные о кабинете успешно сохранены!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении данных о кабинете!", {
                timeout: 2000
            });
        },
    });
};
</script>

<template>
    <Head title="Редактирование кабинета" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Форма редактирования кабинета</h2>
        </template>

        <div class="max-w-lg ml-3 mt-3 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-500 mb-2">Форма редактирования кабинета</h3>
                <span class="text-base font-normal text-gray-500">Заполните все поля и нажмите кнопку "Обновить данные" для сохранения данных.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="mt-6">
                        <div class="grid gap-4">
                            <div class="mb-4">
                                <InputLabel for="name" value="* Наименование кабинета" />

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
                            Обновить данные
                        </PrimaryButton>
                        <Link
                            :href="route('admin.rooms.index')"
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                        >
                            Отмена
                        </Link>
                </div>            
            </form>
        </div>
    </AuthenticatedLayout>
</template>
