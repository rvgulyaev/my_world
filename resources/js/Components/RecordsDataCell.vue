<script setup>
import TableDataCell from "@/Components/TableDataCell.vue";
import Spinner from '@/Components/Spinner.vue';
import {ref} from 'vue';
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import Modal from "@/Components/Modal.vue";

const toast = useToast();
const props = defineProps({
    record: Object
})
const showSpinner = ref(false);
const form = useForm({
    record_id: null
});

const deleteForm = useForm({
    record_id: null
});

const showConfirmDeleteModal = ref(false);

const closeModal = () => {
    showConfirmDeleteModal.value = false;
};

function setPresent(id) {
    showSpinner.value = true;
    form.record_id = id
    form.put(route("records.update", form.record_id), {
        onSuccess: () => {
            showSpinner.value = false;
            toast.success("Отметка о посещении клиента успешно обновлена!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            showSpinner.value = false;
            toast.error("Ошибка при обновлении отметки о посещении клиента!", {
                timeout: 2000
            });
        },
    });
}

function confirmDeleteRecord(id) {
    showConfirmDeleteModal.value = true;
    deleteForm.record_id = id;
}

const deleteRecord = () => {
    closeModal();
    showSpinner.value = true;
    deleteForm.delete(route("records.destroy", deleteForm.record_id), {
        onSuccess: () => {
            showSpinner.value = false;
            toast.success("Запись успешно удалена из расписания!", {
                timeout: 2000
            });
        },
        onError: (e) => {
            showSpinner.value = false;
            toast.error("Ошибка при удалении записи из расписании!", {
                timeout: 2000
            });
        },
    });
};

</script>

<template>
    <TableDataCell class="text-center dark:border-gray-600">{{ record.fio }}</TableDataCell>
    <TableDataCell class="text-center dark:border-gray-600">{{ record.class_name }}</TableDataCell>
    <TableDataCell class="text-center dark:border-gray-600">
        <button v-if="record.is_present === 0" @click="setPresent(record.id)" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-pink-500 dark:text-pink-900">
            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>
            Не был
        </button> 
        <button v-else-if="record.is_present === 1" @click="setPresent(record.id)" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded me-2 dark:bg-cyan-500 dark:text-cyan-900">
            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            Был
        </button> 
        </TableDataCell>
        <TableDataCell class="text-center dark:border-gray-600">
            <button v-if="record.id > 0" @click="confirmDeleteRecord(record.id)" class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-0.5 py-0.5 rounded me-1 dark:bg-pink-300 dark:text-pink-500">
            <svg class="w-3.5 h-3.5 m-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"/>
            </svg>
        </button>
        </TableDataCell>
        <Spinner :showSpinner="showSpinner" /> 

        <Modal :show="showConfirmDeleteModal" @close="closeModal" :maxWidth="'sm'">
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-gray-500">
                        Подтвердите удаление записи из расписания!
                    </h2>
                </div>
                <div class="mt-6 border-t-2 pt-5 border-gray-700 space-x-2 flex items-center justify-center">
                    <PinkButton @click="deleteRecord">Удалить</PinkButton>
                    <SecondaryButton @click="closeModal">Отмена</SecondaryButton>
                </div>
            </div>
        </Modal>
</template>