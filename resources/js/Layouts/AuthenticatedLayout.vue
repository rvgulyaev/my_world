<script setup>
import { ref, onMounted } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { usePermissions } from '@/Composables/permissions';
import axios from 'axios';
import Sidebar from '@/Components/Sidebar.vue';
import moment from 'moment/min/moment-with-locales';

const sessionTimeOut = ref(0);
let currentTime =ref();

onMounted(async ()=>{
   // интервальный запрос раз в два часа
   try {
      const response = await axios.get('/api/getSessionTimeOut');
      sessionTimeOut.value = response.data.session_time_out;

      setInterval(async () => {
         await axios.get('/api/zeroRequest');
      }, sessionTimeOut.value)
   } catch (e) {
      console.log('Ошибка инициализации интервального запроса для отслеживания активности пользователя - ' + e.content)
   }   
   moment.locale('ru');
   setInterval(() => {
      currentTime.value = moment().format('dddd DD.MM.YYYY H:mm:ss')
   })

   const sidebar = document.getElementById('sidebar');

   const toggleSidebarMobile = (sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose) => {
      sidebar.classList.toggle('hidden');
      sidebarBackdrop.classList.toggle('hidden');
      toggleSidebarMobileHamburger.classList.toggle('hidden');
      toggleSidebarMobileClose.classList.toggle('hidden');
   }

   const toggleSidebarMobileEl = document.getElementById('toggleSidebarMobile');
   const sidebarBackdrop = document.getElementById('sidebarBackdrop');
   const toggleSidebarMobileHamburger = document.getElementById('toggleSidebarMobileHamburger');
   const toggleSidebarMobileClose = document.getElementById('toggleSidebarMobileClose');

   toggleSidebarMobileEl.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
   });

   sidebarBackdrop.addEventListener('click', () => {
      toggleSidebarMobile(sidebar, sidebarBackdrop, toggleSidebarMobileHamburger, toggleSidebarMobileClose);
   });
});
</script>

<template>
    <!-- component -->
<!-- This is an example component -->
<div class="min-h-screen bg-gray-50 dark:bg-slate-800">
   <nav class="bg-gray-200 border-b border-gray-300 dark:border-slate-800 fixed z-30 w-full dark:bg-slate-700 dark:text-gray-400">
      <div class="px-3 py-3 lg:px-5 lg:pl-3">
         <div class="flex items-center justify-between">
            <div class="flex items-center justify-start">
               <button id="toggleSidebarMobile" aria-expanded="true" aria-controls="sidebar" class="lg:hidden mr-2 text-gray-600 hover:text-gray-900 cursor-pointer p-2 hover:bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-gray-100 rounded">
                  <svg id="toggleSidebarMobileHamburger" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  <svg id="toggleSidebarMobileClose" class="w-6 h-6 hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                     <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                  </svg>
               </button>
               <a href="#" class="text-xl font-bold flex items-center lg:ml-2.5 space-x-5">
                  <ApplicationLogo class="w-10 h-10 fill-current text-gray-500" />
                  <span class="self-center whitespace-nowrap text-rose-500">Мой МИР</span>
               </a>
            </div>
            <div class="flex items-center">
               <div class="hidden lg:flex items-center">
                  <div class="hidden sm:flex sm:items-center sm:ms-6">
                  {{ currentTime }}
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Выход
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
               </div>
            </div>
         </div>
      </div>
   </nav>
   <div class="flex overflow-hidden pt-16 ">
      <Sidebar />
      <div class="bg-gray-900 opacity-50" id="sidebarBackdrop"></div>
      <div id="main-content" class="w-full bg-gray-50 lg:ml-64 dark:bg-slate-900">
         <main class="w-full pt-6 px-4">
            <slot />
         </main>
      </div>
   </div>
</div>
</template>
