<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Button } from '@/components/ui/button';
import UserFormModal from './UserFormModal.vue';
import { ListTodo } from 'lucide-vue-next';

const usersIndexRoute = () => '/users';

defineProps<{
    users: Array<any>;
    roles: Array<string>;
}>();

const isModalOpen = ref(false);
const userToEdit = ref<any>(null);

const loggedInUserId = computed(() => usePage().props.auth.user.id);


const openEditModal = (user: any) => {
    userToEdit.value = user;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    userToEdit.value = null;
};

const refreshUsers = () => {
    router.reload({ only: ['users'] });
};

const deleteUser = async (user: any) => {
    if (!confirm(`¿Estás seguro de que quieres eliminar al usuario ${user.name}? Esta acción es irreversible.`)) {
        return;
    }

    try {
        await axios.delete(`/users/${user.id}`);
        
        // Recargamos la lista para ver el cambio
        refreshUsers();
        alert(`Usuario ${user.name} eliminado correctamente.`);

    } catch (error: any) {
        if (error.response && error.response.status === 403) {
            // Esto captura el error de 'No puedes eliminar tu propia cuenta.'
            alert(error.response.data.message);
        } else {
            console.error(error);
            alert('Ocurrió un error al eliminar el usuario.');
        }
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gestión de Usuarios',
        // FIX: Usamos el helper estable
        href: usersIndexRoute(),
    },
];
</script>

<template>
    <Head title="Gestión de Usuarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div
                class="relative min-h-min flex-1 rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border"
            >
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold">Gestión de Usuarios</h1>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol Actual</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ user.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                        {{ user.roles[0]?.name.toUpperCase() || 'SIN ROL' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        @click="openEditModal(user)"
                                        class="mr-2"
                                    >
                                        Editar Rol
                                    </Button>

                                    <Button
                                        variant="destructive"
                                        size="sm"
                                        @click="deleteUser(user)"
                                    >
                                        Eliminar
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="users.length === 0" class="text-gray-500 mt-4 text-center p-4">
                    No hay otros usuarios registrados en el sistema.
                </div>
            </div>
        </div>

        <UserFormModal 
            :show="isModalOpen"
            :user-to-edit="userToEdit"
            :all-roles="roles"
            @close="closeModal"
            @user-saved="refreshUsers"
        />
    </AppLayout>
</template>