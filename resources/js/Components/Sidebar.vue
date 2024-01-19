<script setup lang="ts">
import SidebarLink from '@/Components/SidebarLink.vue';
import { usePermissions } from '@/Composables/permissions';
import { ref } from 'vue'

const { hasRole } = usePermissions();

let showAdminSubMenu = ref(false);

const collapseAdminMenu = () => {
   showAdminSubMenu.value = !showAdminSubMenu.value
}
let showReportSubMenu = ref(false);

const collapseReportMenu = () => {
   showReportSubMenu.value = !showReportSubMenu.value
}

</script>

<template>
    <aside id="sidebar" class="fixed hidden z-20 h-full top-0 left-0 pt-16 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75" aria-label="Sidebar">
         <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-300 dark:border-gray-700 bg-white pt-0 dark:bg-slate-700 dark:text-gray-400">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
               <div class="flex-1 px-3 bg-white divide-y space-y-1 dark:bg-slate-700 dark:text-gray-400">
                  <ul class="space-y-2 pb-2">
                    <li>
                        <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                           </svg>
                           <span class="ml-3">Главная</span>
                        </SidebarLink>
                     </li>
                     <li>
                        <SidebarLink :href="route('records.index')" :active="route().current('records.*')" v-if="hasRole('admin') | hasRole('moderator')">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                           </svg>
                           <span class="ml-3">Расписание занятий</span>
                        </SidebarLink>
                     </li>
                     <li>
                        <SidebarLink :href="route('clients.index')" :active="route().current('clients.*')" v-if="hasRole('admin') | hasRole('moderator')">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                           </svg>
                           <span class="ml-3">Клиенты</span>
                        </SidebarLink>
                     </li>
                     <li>
                        <SidebarLink :href="route('tasks.index')" :active="route().current('tasks.*')">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m7.875 14.25 1.214 1.942a2.25 2.25 0 0 0 1.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 0 1 1.872 1.002l.164.246a2.25 2.25 0 0 0 1.872 1.002h2.092a2.25 2.25 0 0 0 1.872-1.002l.164-.246A2.25 2.25 0 0 1 16.954 9h4.636M2.41 9a2.25 2.25 0 0 0-.16.832V12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 0 1 .382-.632l3.285-3.832a2.25 2.25 0 0 1 1.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0 0 21.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 0 0 2.25 2.25Z" />
                           </svg>
                           <span class="ml-3">Задачи</span>
                        </SidebarLink>
                     </li>
                     <li>
                        <button v-if="hasRole('admin')" v-bind:class = "(route().current('admin.*'))?'bg-blue-100 dark:bg-gray-800':''" @click="collapseAdminMenu" 
                        class="text-base text-gray-900 dark:text-blue-200 font-normal rounded-md hover:bg-blue-100 dark:hover:bg-gray-800 hover:text-blue-900 dark:hover:text-blue-200 flex items-center p-2 group ">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                           </svg>
                           <span class="flex-1 ml-3 text-left whitespace-nowrap">Администрирование</span>
                           <svg v-if="showAdminSubMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 ml-4">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                           </svg>
                           <svg v-if="!showAdminSubMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 ml-4">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                           </svg>
                        </button>
                        <ul class="pl-3 mt-1" v-if="showAdminSubMenu">
                           <li class="pb-1">
                              <SidebarLink :href="route('admin.users.index')" :active="route().current('admin.users.*')" v-if="hasRole('admin')">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                 </svg>

                                 <span class="ml-3">Пользователи</span>
                              </SidebarLink>
                           </li>
                           <li class="pb-1">
                              <SidebarLink :href="route('admin.classes.index')" :active="route().current('admin.classes.*')" v-if="hasRole('admin')">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                 </svg>
                                 <span class="ml-3">Справочник направлений</span>
                              </SidebarLink>
                           </li>
                           <li class="pb-1">
                              <SidebarLink :href="route('admin.rooms.index')" :active="route().current('admin.rooms.index')" v-if="hasRole('admin')">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                 </svg>
                                 <span class="ml-3">Справочник кабинетов</span>
                              </SidebarLink>
                           </li>
                           <li class="pb-1">
                              <SidebarLink :href="route('admin.time-ranges.index')" :active="route().current('admin.time-ranges.index')" v-if="hasRole('admin')">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                 </svg>
                                 <span class="ml-3">Справочник времени</span>
                              </SidebarLink>
                           </li>
                        </ul>
                     </li>
                     <li>
                        <button v-if="hasRole('admin') | hasRole('moderator')" v-bind:class = "(route().current('reports.*'))?'bg-blue-100 dark:bg-gray-800':''" @click="collapseReportMenu" 
                        class="text-base text-gray-900 dark:text-blue-200 font-normal rounded-md hover:bg-blue-100 dark:hover:bg-gray-800 hover:text-blue-900 dark:hover:text-blue-200 flex items-center p-2 group w-full">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                           </svg>
                           <span class="flex items-center justify-between w-full">
                              <span class="ml-3 whitespace-nowrap">Отчеты</span>
                              <svg v-if="showReportSubMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                              <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                              </svg>
                              <svg v-if="!showReportSubMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                              </svg>
                           </span>
                        </button>
                        <ul class="pl-3 mt-1" v-if="showReportSubMenu">
                           <li class="pb-1">
                              <SidebarLink :href="route('reports.clients.index')" :active="route().current('reports.clients.*')" v-if="hasRole('admin') | hasRole('moderator')">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                 </svg>
                                 <span class="ml-3">Учет посещений</span>
                              </SidebarLink>
                           </li>
                           <li class="pb-1">
                              <SidebarLink :href="route('reports.specialist.index')" :active="route().current('reports.specialist.*')" v-if="hasRole('admin') | hasRole('moderator')">
                                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                 </svg>
                                 <span class="ml-3">Отчет по специалистам</span>
                              </SidebarLink>
                           </li>
                        </ul>
                     </li>
                     <li class="border-t border-slate-300 pt-3">
                        <SidebarLink :href="route('logout')" method="post" as="button" class="w-full">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 7.5 3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z" />
                           </svg>
                           <span class="ml-3">Выход</span>
                        </SidebarLink>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </aside>
</template>

<style lang="scss" scoped>

</style>