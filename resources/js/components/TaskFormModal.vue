<script setup lang="ts">
import { computed, watch, ref } from 'vue';
import axios from 'axios';
import { type Task } from '@/types';
import {
    Dialog,
    DialogContent,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

const formInputClasses = 'mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';

interface Props {
    show: boolean;
    mode?: 'create' | 'edit';
    taskToEdit?: Task | null;
}
const props = withDefaults(defineProps<Props>(), {
    show: false,
    mode: 'create',
    taskToEdit: null,
});

const emit = defineEmits(['close', 'task-saved']);

const form = ref({
    title: '',
    description: '',
    status: 'pending',
    due_date: '',
});
const isProcessing = ref(false);
const errors = ref<Record<string, string>>({});

const title = computed(() => {
    return props.mode === 'create' ? 'Create New Task' : 'Edit Task';
});

watch(() => props.show, (newValue) => {
    if (newValue === true) {
        errors.value = {};
        isProcessing.value = false;

        if (props.mode === 'edit' && props.taskToEdit) {
            form.value.title = props.taskToEdit.title;
            form.value.description = props.taskToEdit.description || '';
            form.value.status = props.taskToEdit.status;
            form.value.due_date = props.taskToEdit.due_date || '';
        } else {
            form.value.title = '';
            form.value.description = '';
            form.value.status = 'pending';
            form.value.due_date = '';
        }
    }
});

const closeModal = () => {
    emit('close');
};

const submitForm = async () => {
    isProcessing.value = true;
    errors.value = {};

    const payload = {
        title: form.value.title,
        description: form.value.description,
        status: form.value.status,
        due_date: form.value.due_date,
    };

    try {
        if (props.mode === 'create') {
            await axios.post('/tasks', payload);
        } else if (props.taskToEdit) {
            await axios.put(`/tasks/${props.taskToEdit.id}`, payload);
        }
        
        emit('task-saved');
        closeModal();

    } catch (error: any) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error(error);
            alert('An unexpected error occurred. Please try again.');
        }
    } finally {
        isProcessing.value = false;
    }
};
</script>

<template>
    <Dialog :open="show" @update:open="show = false; closeModal()">
        <DialogContent class="sm:max-w-xl">
            <form @submit.prevent="submitForm">
                <DialogHeader>
                    <DialogTitle>{{ title }}</DialogTitle>
                </DialogHeader>

                <div class="grid gap-4 py-4">
                    <div>
                        <Label for="title">Title</Label>
                        <Input
                            id="title"
                            v-model="form.title"
                            type="text"
                            class="mt-1"
                            required
                        />
                        <InputError class="mt-2" :message="errors.title?.[0]" />
                    </div>

                    <div class="mt-4">
                        <Label for="description">Description</Label>
                        <textarea
                            id="description"
                            v-model="form.description"
                            :class="formInputClasses"
                            rows="3"
                        ></textarea>
                        <InputError class="mt-2" :message="errors.description?.[0]" />
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div>
                            <Label for="status">Status</Label>
                            <select
                                id="status"
                                v-model="form.status"
                                :class="formInputClasses"
                            >
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                            <InputError class="mt-2" :message="errors.status?.[0]" />
                        </div>
                        
                        <div>
                            <Label for="due_date">Due Date</Label>
                            <Input
                                id="due_date"
                                v-model="form.due_date"
                                type="date"
                                class="mt-1"
                            />
                            <InputError class="mt-2" :message="errors.due_date?.[0]" />
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button type="button" variant="ghost" @click="closeModal">
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :class="{ 'opacity-25': isProcessing }"
                        :disabled="isProcessing"
                    >
                        {{ isProcessing ? 'Saving...' : 'Save' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>