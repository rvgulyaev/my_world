<script setup>
import { Link, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePermissions } from '@/Composables/permissions';

const { hasRole } = usePermissions();
</script>

<template>
    <Head title="Главная страница" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-gray-800 dark:text-gray-200 leading-tight">Главная</h2>
        </template>

        
                     <div class="mb-4 flex items-center justify-between">
                        <div>
                           <h3 class="text-xl font-bold text-gray-900 dark:text-gray-300 mb-2">Главная страница</h3>
                        </div>
                     </div>

                     <div class="container flex flex-col mx-auto h-screen">
                        <div class="w-full draggable">
                           <div class="container flex flex-col items-center gap-16 mx-auto my-3">
                              <div class="grid w-full grid-cols-1 gap-7 lg:grid-cols-3">
                                 <Link :href="route('records.index')">
                                    <div class="flex flex-col items-center gap-3 px-8 py-10 bg-white  dark:bg-slate-700 rounded-2xl border-gray-200 dark:border-slate-500 border shadow-lg transition duration-300 hover:scale-105">
                                       <span>
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-blue-200 dark:text-pink-500 group-hover:text-blue-400 transition duration-75">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                          </svg>
                                       </span>
                                       <p class="text-2xl text-dark-grey-900 uppercase dark:text-indigo-500">Расписание занятий</p>
                                       <p class="text-center leading-7 text-dark-grey-600 dark:text-slate-300">Раздел для ведения расписания занятий и отслеживания посещений</p>
                                       <span
                                       class="inline-flex items-center px-4 py-2 bg-indigo-300 border border-transparent rounded-md font-semibold text-xs text-indigo-800 uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                       >
                                       Перейти
                                       </span>
                                    </div>                                    
                                 </Link>
                                 <Link :href="route('clients.index')"  v-if="hasRole('admin') | hasRole('moderator')">
                                    <div class="flex flex-col items-center gap-3 px-8 py-10 bg-white dark:bg-slate-700 rounded-2xl dark:border-slate-500 border shadow-lg transition duration-300 hover:scale-105">
                                       <span>
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-20 h-20 text-blue-200 flex-shrink-0 dark:text-pink-500 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                          </svg>
                                       </span>
                                       <p class="text-2xl text-dark-grey-900 uppercase dark:text-indigo-500">Список клиентов</p>
                                       <p class="text-center leading-7 text-dark-grey-600 dark:text-slate-300">Раздел для ведения списка клиентов центра. Список используется в расписании занятий.</p>
                                       <span
                                       class="inline-flex items-center px-4 py-2 bg-indigo-300 border border-transparent rounded-md font-semibold text-xs text-indigo-800 uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                       >
                                       Перейти
                                       </span>
                                    </div>                                    
                                 </Link>                                 
                                 <Link :href="route('tasks.index')">
                                    <div class="flex flex-col items-center gap-3 px-8 py-10 bg-white dark:bg-slate-700 rounded-2xl dark:border-slate-500 border shadow-lg transition duration-300 hover:scale-105">
                                       <span>
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-blue-200 dark:text-pink-500 group-hover:text-blue-400 transition duration-75">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                          </svg>
                                       </span>
                                       <p class="text-2xl text-dark-grey-900 uppercase dark:text-indigo-500">Список задача</p>
                                       <p class="text-center leading-7 text-dark-grey-600 dark:text-slate-300">Раздел для ведения списка задач для администратора центра.</p>
                                       <span
                                       class="inline-flex items-center px-4 py-2 bg-indigo-300 border border-transparent rounded-md font-semibold text-xs text-indigo-800 uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                       >
                                       Перейти
                                       </span>
                                    </div>                                    
                                 </Link>
                              </div>
                              <div class="grid w-full grid-cols-1 gap-7 lg:grid-cols-3">
                                 <Link :href="route('admin.users.index')" v-if="hasRole('admin')">
                                    <div class="flex flex-col items-center gap-3 px-8 py-10 bg-white dark:bg-slate-700 rounded-2xl dark:border-slate-500 border shadow-lg transition duration-300 hover:scale-105">
                                       <span>
                                          <svg class="w-20 h-20 text-blue-200 flex-shrink-0 dark:text-pink-500 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                             <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                          </svg>
                                       </span>
                                       <p class="text-2xl text-dark-grey-900 uppercase dark:text-indigo-500">Список пользователей</p>
                                       <p class="text-center leading-7 text-dark-grey-600 dark:text-slate-300">Раздел для ведения пользователей, включая специалистов, с распределением прав доступа.</p>
                                       <span
                                       class="inline-flex items-center px-4 py-2 bg-indigo-300 border border-transparent rounded-md font-semibold text-xs text-indigo-800 uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                       >
                                       Перейти
                                       </span>
                                    </div>                                    
                                 </Link>
                              </div>
                           </div>
                        </div>
                     </div>
    </AuthenticatedLayout>
</template>
