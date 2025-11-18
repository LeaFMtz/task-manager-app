<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Task } from '@/types';
import { Button } from '@/components/ui/button';
import TaskFormModal from '@/components/TaskFormModal.vue';
import { dashboard } from '@/routes';

const page = usePage();

defineProps<{
    tasks: Task[];
}>();

const userPermissions = computed(() => usePage().props.auth.user_permissions || []);
const canCreateTasks = computed(() => userPermissions.value.includes('task-create'));

const filterStatus = ref<string | null>(null); 

const isModalOpen = ref(false);
const modalMode = ref<'create' | 'edit'>('create');
const taskToEdit = ref<Task | null>(null);

const fetchTasks = (status: string | null = null) => {
    filterStatus.value = status; 

    router.get(
        page.url, 
        { status: status }, 
        {         
            preserveScroll: true,
            only: ['tasks'], 
            preserveState: true,
        }
    );
};

const openCreateModal = () => {
    modalMode.value = 'create';
    taskToEdit.value = null;
    isModalOpen.value = true;
};

const openEditModal = (task: Task) => {
    modalMode.value = 'edit';
    taskToEdit.value = task;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    taskToEdit.value = null;
};

const deleteTask = async (task: Task) => {
    if (!confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
        return;
    }

    try {
        await axios.delete(`/tasks/${task.id}`);
        fetchTasks(filterStatus.value);
    } catch (error: any) {
        if (error.response && error.response.status === 403) {
            alert(error.response.data.message);
        } else {
            console.error(error);
            alert('Se ha producido un error al eliminar la tarea.');
        }
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My Tasks',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Tasks" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 p-4 md:min-h-min dark:border-sidebar-border">
                
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-xl font-semibold">My Tasks</h1>
                    <Button v-if="canCreateTasks" @click="openCreateModal">
                        Create Task
                    </Button>
                </div>

                <div class="flex flex-wrap gap-2 mb-6">
                    <Button 
                        :variant="filterStatus === null ? 'default' : 'outline'"
                        size="sm"
                        @click="fetchTasks(null)"
                    >
                        All
                    </Button>
                    <Button 
                        :variant="filterStatus === 'pending' ? 'default' : 'outline'"
                        size="sm"
                        @click="fetchTasks('pending')"
                    >
                        Pending
                    </Button>
                    <Button 
                        :variant="filterStatus === 'in_progress' ? 'default' : 'outline'"
                        size="sm"
                        @click="fetchTasks('in_progress')"
                    >
                        In Progress
                    </Button>
                    <Button 
                        :variant="filterStatus === 'completed' ? 'default' : 'outline'"
                        size="sm"
                        @click="fetchTasks('completed')"
                    >
                        Completed
                    </Button>
                </div>

                <ul class="space-y-3">
                    <li
                        v-for="task in tasks"
                        :key="task.id"
                        class="flex items-center justify-between rounded border p-3"
                    >
                        <div>
                            <p class="font-semibold">{{ task.title }}</p>
                            <span 
                                class="text-xs font-medium px-2 py-1 rounded-full"
                                :class="{
                                    'bg-yellow-100 text-yellow-800': task.status === 'pending',
                                    'bg-blue-100 text-blue-800': task.status === 'in_progress',
                                    'bg-green-100 text-green-800': task.status === 'completed'
                                }"
                            >
                                {{ task.status.replace('_', ' ') }}
                            </span>
                        </div>
                        
                        <div class="space-x-2">
                            <Button
                                v-if="userPermissions.includes('task-update-all') || userPermissions.includes('task-update-own')"
                                variant="outline"
                                size="sm"
                                @click="openEditModal(task)"
                            >
                                Editar
                            </Button>
                            
                            <Button
                                v-if="userPermissions.includes('task-delete-all') || userPermissions.includes('task-delete-own')"
                                variant="destructive"
                                size="sm"
                                @click="deleteTask(task)"
                            >
                                Borrar
                            </Button>
                        </div>
                    </li>
                </ul>
                
                <div v-if="tasks.length === 0" class="text-gray-500 mt-4 text-center">
                    No se encontraron tareas para este filtro.
                </div>
            </div>
        </div>

        <TaskFormModal
            :show="isModalOpen"
            :mode="modalMode"
            :task-to-edit="taskToEdit"
            @close="closeModal"
            @task-saved="() => fetchTasks(filterStatus)"
        />
    </AppLayout>
</template>