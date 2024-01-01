<script setup lang="ts">
import SidebarLink from '@/Components/SidebarLink.vue';
import { usePermissions } from '@/Composables/permissions';

const { hasRole } = usePermissions();

</script>

<template>
    <aside id="sidebar" class="fixed hidden z-20 h-full top-0 left-0 pt-16 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75 " aria-label="Sidebar">
         <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 dark:border-gray-700 bg-white pt-0 dark:bg-gray-900 dark:text-gray-400">
            <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
               <div class="flex-1 px-3 bg-white divide-y space-y-1 dark:bg-gray-900 dark:text-gray-400">
                  <ul class="space-y-2 pb-2">
                    <li>
                        <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')">
                           <svg class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                              <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                           </svg>
                           <span class="ml-3">Главная</span>
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
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                           </svg>
                           <span class="ml-3">Задачи</span>
                        </SidebarLink>
                     </li>
                     <li>
                        <SidebarLink :href="route('users.index')" :active="route().current('users.*')" v-if="hasRole('admin')">
                           <svg class="w-6 h-6 text-blue-200 flex-shrink-0 group-active:text-blue-400 group-hover:text-blue-400 transition duration-75" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                              <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                           </svg>
                           <span class="ml-3">Пользователи</span>
                        </SidebarLink>
                     </li>
                     <li class="pt-2 border-t dark:border-gray-700">
                        <SidebarLink :href="route('logout')" method="post">
                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" data-slot="icon" class="w-6 h-6 text-blue-200 group-hover:text-blue-400 transition duration-75">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
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