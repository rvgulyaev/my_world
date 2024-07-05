<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { useToast } from "vue-toastification";

const toast = useToast();
const props  = defineProps({
    class_group_item: {
        type: Object,
        required: true
    },
})

// Add User Modal
const form = useForm({
    name: props.class_group_item?.name,
});
const submit = async () => {
    form.put(route('admin.classes_groups.update', props.class_group_item), {
        onSuccess: () => {
            toast.success("Данные о группе направлений успешно сохранены!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении данных о группе направлений!", {
                timeout: 2000
            });
        },
    });
};
</script>

<template>
    <Head title="Редактирование группы направлений" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="leading-tight text-gray-800 dark:text-gray-200">Форма редактирования группы направлений</h2>
        </template>

        <div class="max-w-lg p-6 mt-3 ml-3 bg-white rounded-md shadow-md dark:bg-gray-700">
            <div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">Форма редактирования группы направлений</h3>
                <span class="text-base font-normal text-gray-500">Заполните все поля и нажмите кнопку "Обновить данные" для сохранения данных.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="mt-6">
                        <div class="grid gap-4">
                            <div class="mb-4">
                                <InputLabel for="name" value="* Наименование группы направлений" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="block w-full mt-1"
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
                <div class="pt-6 mt-6 space-x-4 border-t border-gray-500">
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Обновить данные
                        </PrimaryButton>
                        <Link
                            :href="route('admin.classes_groups.index')"
                            class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25"
                        >
                            Отмена
                        </Link>
                </div>            
            </form>
        </div>
    </AuthenticatedLayout>
</template>
