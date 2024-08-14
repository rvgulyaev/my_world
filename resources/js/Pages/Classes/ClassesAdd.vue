<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { useToast } from "vue-toastification";
import VueMultiselect from 'vue-multiselect';
import Switch from '@/Components/Switch.vue';
import { watch } from 'vue';

const toast = useToast();

const props = defineProps({
    classes_groups: {
        type: Object,
        required: true
    }
})

// Add User Modal
const form = useForm({
    name: "",
    has_group: 0,
    class_group_id: {
        'id': 0,
        'name': 'Вне группы'
    },
    order: 9999
});

const submit = () => {
    form.post(route("admin.classes.store"), {
        onSuccess: () => {
            toast.success("Данные о типе занятий успешно сохранены!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            toast.error("Ошибка при сохранении данных о типе занятий!", {
                timeout: 2000
            });
        },
    });
};

watch(
    () => form.has_group == false,
    () => {
        form.class_group_id = {id:0,name:'Вне группы'}
    }
);
</script>

<template>
    <Head title="Форма добавления типа занятий" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="leading-tight text-gray-800 dark:text-gray-200">
                Форма добавления типа занятий
            </h2>
        </template>

        <div class="max-w-lg p-6 mt-3 ml-3 bg-white rounded-md shadow-md dark:bg-gray-700">
            <div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">
                    Форма добавления типа занятий
                </h3>
                <span class="text-base font-normal text-gray-500">Поля помеченные * обязательны для заполнения.</span>
            </div>
            <form @submit.prevent="submit">
                <div class="grid gap-4">
                    <div>
                        <div class="mb-4">
                            <InputLabel for="name" value="* Наименование типа занятий" />

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
                        <div class="mb-4">
                            <Switch
                            v-model:checked="form.has_group" label="Направление входит в группу?"
                            />

                            <InputError class="mt-2" :message="form.errors.specialist" />
                        </div>
                        <div class="mb-4" :class="(form.has_group==0?'hidden':'')">
                            <InputLabel for="roles" value="Группы направлений" />
                            <VueMultiselect
                                v-model="form.class_group_id"
                                :options="classes_groups"
                                :multiple="false"
                                :close-on-select="true"
                                placeholder="Выберите группу"
                                label="name"
                                track-by="id"
                                />
                            <InputError class="mt-2" :message="form.errors.class_group_id" />
                        </div> 
                        <div class="mb-4">
                            <InputLabel for="order" value="* Номер по порядку (по-умолчанию 9999 - конец списка)" />

                            <TextInput
                                    id="order"
                                    type="text"
                                    class="block w-full mt-1"
                                    v-model="form.order"
                                    placeholder="9999"
                                />

                            <InputError
                                class="mt-2"
                                :message="form.errors.order"
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
                        Добавить тип занятий
                    </PrimaryButton>
                    <Link
                        :href="route('admin.classes.index')"
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25"
                    >
                        Отмена
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
