<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import VueMultiselect from 'vue-multiselect';
import { onMounted, watch } from "vue";
import Switch from '@/Components/Switch.vue';


const props  = defineProps({
    user: {
        type: Object,
        required: true
    },
    roles: Array,
})

// Add User Modal
const form = useForm({
    name: props.user?.name,
    username: props.user?.username,
    specialist: props.user?.specialist,
    phone: props.user?.phone,
    password: '',
    password_confirmation: '',
    roles: [],
});
const submit = () => {
    form.put(route('users.update', props.user?.id), {
        onSuccess: () => {
            form.reset('name', 'username', 'specialist', 'phone', 'password', 'password_confirmation', 'roles');
        },
    });
};
onMounted(() => {
  form.roles = props.user?.roles;
});
watch(
  () => props.user,
  () => {
    form.roles = props.user?.roles
  }
);
</script>

<template>
    <Head title="Редактирование пользователя" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Форма редактирования пользователя</h2>
        </template>

        <div class="max-w-lg ml-6 mt-6 p-6 bg-white dark:bg-gray-700 rounded-md shadow-md">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-indigo-400 mb-2">Форма редактирования пользователя</h3>
                <span class="text-base font-normal text-gray-500">Заполните все поля и нажмите кнопку "Изменить" для сохранения данных.</span>
            </div>
            <div class="mt-6">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel for="name" value="ФИО" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="username" value="Имя пользователя" />

                        <TextInput
                            id="username"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.username"
                            required
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.username" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="phone" value="Телефон" />

                        <TextInput
                            id="phone"
                            type="text"
                            v-mask="'+7(###)-###-##-##'"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            required
                            autocomplete="phone"
                        />

                        <InputError class="mt-2" :message="form.errors.phone" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password" value="Пароль" />

                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password"
                            autocomplete="new-password"
                        />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password_confirmation" value="Подтверждение пароля" />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            v-model="form.password_confirmation"
                            autocomplete="new-password"
                        />

                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="roles" value="Права пользователя" />
                        <VueMultiselect
                            v-model="form.roles"
                            :options="roles"
                            :multiple="true"
                            :close-on-select="true"
                            placeholder="Выберите права"
                            label="name"
                            track-by="id"
                            />
                            <InputError class="mt-2" :message="form.errors.roles" />
                    </div>

                    <div class="mb-4">
                        <Switch
                        v-model:checked="form.specialist" label="Пользователь является специалистом?"
                        />

                        <InputError class="mt-2" :message="form.errors.specialist" />
                    </div>

                <div class="mt-6 flex space-x-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Обновить
                    </PrimaryButton>
                    <Link :href="route('users.index')" 
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        Отмена
                    </Link>
                </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
@media(prefers-color-scheme: dark) {
    .multiselect__spinner {
        background: rgb(31 41 55 / var(--tw-bg-opacity));
      }
      .multiselect--disabled {
        background: rgb(31 41 55 / var(--tw-bg-opacity));
      }
      .multiselect__input,
        .multiselect__single {
            background: rgb(31 41 55 / var(--tw-bg-opacity));
        }
      .multiselect__tags {
        border: 1px solid rgb(55 65 81 / var(--tw-border-opacity));
        background: rgb(31 41 55 / var(--tw-bg-opacity));
      }
      .multiselect__content-wrapper {
      background: rgb(31 41 55 / var(--tw-bg-opacity));
      border: 1px solid rgb(55 65 81 / var(--tw-border-opacity));
    }
    .multiselect--above .multiselect__content-wrapper {
      border-top: 1px solid rgb(55 65 81 / var(--tw-border-opacity));
    }
  }
</style>
