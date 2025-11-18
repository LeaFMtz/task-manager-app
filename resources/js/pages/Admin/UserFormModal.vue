<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { ListTodo } from 'lucide-vue-next';

interface Props {
    show: boolean;
    userToEdit: any | null;
    allRoles: Array<string>;
}
const props = defineProps<Props>();
const emit = defineEmits(['close', 'userSaved']);

const processing = ref(false);
const errors = ref<Record<string, string>>({});
const form = ref({
    role_name: '',
});

watch(() => props.show, (newValue) => {
    if (newValue && props.userToEdit) {
        form.value.role_name = props.userToEdit.roles[0]?.name || ''; 
        errors.value = {};
    }
});

const submitForm = async () => {
    if (!props.userToEdit) return;

    processing.value = true;
    errors.value = {};

    try {
        await axios.put(`/users/${props.userToEdit.id}`, {
            role_name: form.value.role_name,
        });
        
        emit('userSaved');
        closeModal();
    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else if (error.response && error.response.status === 403) {
            alert(error.response.data.message || 'Error de Autorización');
        } else {
            console.error('Error Inesperado:', error);
            alert('Ocurrió un error inesperado. Inténtalo de nuevo.');
        }
    } finally {
        processing.value = false;
    }
};

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <Dialog :open="show" @update:open="show = false; closeModal()">
        <DialogContent class="sm:max-w-md dark:bg-gray-900 dark:text-white">
            <form @submit.prevent="submitForm">
                <DialogHeader class="mb-4">
                    <ListTodo class="h-6 w-6 text-[#f53003] dark:text-[#FF4433] mx-auto mb-2" />
                    <DialogTitle class="text-xl font-bold text-center">
                        Cambiar Rol: {{ props.userToEdit?.name }}
                    </DialogTitle>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <Label for="role_name">Rol</Label>
                        <select
                            id="role_name"
                            v-model="form.role_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-[#f53003] focus:ring-[#f53003]"
                        >
                            <option value="" disabled>Selecciona un rol</option>
                            <option 
                                v-for="role in allRoles" 
                                :key="role" 
                                :value="role"
                            >
                                {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                            </option>
                        </select>
                        <InputError class="mt-2" :message="errors.role_name?.[0]" />
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="ghost" @click="closeModal">
                        Cancelar
                    </Button>
                    <Button
                        type="submit"
                        :disabled="processing"
                        class="bg-[#f53003] hover:bg-[#FF4433] text-white dark:bg-[#FF4433] dark:hover:bg-[#f53003]"
                    >
                        {{ processing ? 'Guardando...' : 'Guardar Rol' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>