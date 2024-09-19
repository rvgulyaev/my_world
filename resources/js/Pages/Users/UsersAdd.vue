<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import VueMultiselect from 'vue-multiselect';
import Switch from '@/Components/Switch.vue';


const props  = defineProps({
    roles: Array,
})

// Add User Modal
const form = useForm({
    name: '',
    username: '',
    specialist: 0,
    phone: '',
    password: '',
    password_confirmation: '',
    roles: [],
});
const submit = () => {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            form.reset('name', 'username', 'specialist', 'phone', 'password', 'password_confirmation', 'roles');
        },
    });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="leading-tight text-gray-800 dark:text-gray-200">Форма добавления пользователя</h2>
        </template>
        <div class="flex">

            <div class="max-w-lg p-6 mt-6 ml-6 bg-white rounded-md shadow-md dark:bg-gray-700">
            <div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">Форма добавления пользователя</h3>
                <span class="text-base font-normal text-gray-500">Заполните все поля и нажмите кнопку "Добавить" для сохранения данных.</span>
            </div>
            <div class="mt-6">
                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <InputLabel for="name" value="ФИО" />

                        <TextInput
                            id="name"
                            type="text"
                            class="block w-full mt-1"
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
                            class="block w-full mt-1"
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
                            type="tel"
                            v-mask="'+7(###)-###-##-##'"
                            class="block w-full mt-1"
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
                            class="block w-full mt-1"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />

                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="mb-4">
                        <InputLabel for="password_confirmation" value="Подтверждение пароля" />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="block w-full mt-1"
                            v-model="form.password_confirmation"
                            required
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
                <div class="flex mt-6 space-x-4">
                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Добавить
                    </PrimaryButton>
                    <Link :href="route('admin.users.index')" 
                        class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-800 dark:border-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                        Отмена
                    </Link>
                </div>
                </form>
            </div>
        </div>
        <div class="max-w-lg p-6 mt-6 ml-6 bg-white rounded-md shadow-md dark:bg-gray-700">
            <div>
                <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-indigo-500">Справочно</h3>
                <span class="text-base font-normal text-gray-500">Описание прав пользователей.</span>
            </div>
            <div class="mt-6">
                <span class="text-lg font-bold">admin</span> - администратор сайта
                <div>Имеет полный доступ ко всем разделам сайта, разрешены все действия.</div>
                <span class="text-lg font-bold">moderator</span> - администратор центра (управляющий)
                <div>Имеет доступ к разделам сайта - Расписание занятий, Клиенты, Задачи, Отчеты</div>
                <div>Раздел Расписание занятий - разрешено добавлять/удалять записи в расписании, добавлять примечание, устанавливать отметку о посещении, запрещено - переход в корзину</div>
                <div>Раздел Клиенты - разрешено добавлять/удалять клиентов (включая пожелания родителей), запрещено - переход в корзину</div>
                <div>Раздел Задачи - разрешено просмотр задач, простановка отметки о выполнении (включая добавление комментария), запрещено - добавление/удаление задач, переход в корзину</div>
                <div>Раздел Отчеты - разрешен полный доступ</div>
                <span class="text-lg font-bold">user</span> - пользователь сайта (специалист)
                <div>Имеет доступ к разделам сайта - Расписание занятий, Задачи, Отчеты</div>
                <div>Раздел Расписание занятий - разрешен просмотр, добавление/удаление записей, добавление примечания, установка отметки о посещении, но только личного расписания пользователя, запрещено - переход в корзину</div>
                <div>Раздел Задачи - разрешено просмотр задач, добавление задача, удаление созданных пользователем задач, запрещено - простановка отметки по выполнении, переход в корзину</div>
                <div>Раздел Отчеты - разрешен полный доступ, но только по личным данным, исключая данные других специалистов.</div>
            </div>
        </div>
        </div>
    </AuthenticatedLayout>
</template>
