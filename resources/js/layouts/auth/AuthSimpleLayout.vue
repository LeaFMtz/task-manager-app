<script setup lang="ts">
// Eliminamos import AppLogoIcon
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';
import { ListTodo } from 'lucide-vue-next'; // NUEVO: Importamos el icono

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <Link 
            :href="home()" 
            class="mb-6 inline-flex items-center space-x-2 text-sm font-semibold text-gray-500 hover:text-[#f53003] dark:text-gray-400 dark:hover:text-[#FF4433]"
        >
            <ListTodo class="h-4 w-4" />
            <span>Volver a Task Manager</span>
        </Link>
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                
                <div class="flex flex-col items-center gap-4">
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>