<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { ListTodo, CheckSquare } from 'lucide-vue-next'; // Importamos íconos para branding

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

// Helper para el Home (usamos '/' o el nombre de tu ruta 'home')
const homeRoute = () => '/'; 
</script>

<template>
    <Head title="Welcome | Task Manager">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a] dark:text-[#EDEDEC]"
    >
        <header
            class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-between">
                <Link :href="homeRoute()" class="flex items-center space-x-2 text-lg font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                    <ListTodo class="h-6 w-6 text-[#f53003] dark:text-[#FF4433]" />
                    <span>Task Manager</span>
                </Link>

                <div class="flex items-center gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC dark:hover:border-[#62605b]"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="inline-block rounded-sm border border-[#f53003] bg-[#f53003] px-5 py-1.5 text-sm leading-normal text-white hover:bg-[#FF4433] dark:border-[#FF4433] dark:bg-[#FF4433]"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </nav>
        </header>

        <div
            class="flex w-full items-center justify-center lg:grow"
        >
            <main
                class="flex w-full max-w-[335px] flex-col overflow-hidden rounded-lg shadow-xl lg:max-w-4xl lg:flex-row"
            >
                <div
                    class="flex-1 rounded-t-lg bg-white p-8 lg:rounded-l-lg lg:rounded-t-none lg:p-20 dark:bg-[#161615] dark:text-[#EDEDEC]"
                >
                    <h1 class="mb-4 text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">
                        Task Manager.
                    </h1>
                    <p class="mb-6 text-lg text-[#706f6c] dark:text-[#A1A09A]">
                        Organiza tu día. Prioriza tus tareas. Aumenta tu productividad.
                    </p>
                    <ul class="mb-8 space-y-3 text-sm">
                        <li class="flex items-center space-x-2">
                            <CheckSquare class="h-4 w-4 text-[#f53003] dark:text-[#FF4433]"/>
                            <span>Acceso basado en roles (Admin, Editor, Viewer).</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <CheckSquare class="h-4 w-4 text-[#f53003] dark:text-[#FF4433]"/>
                            <span>Gestión de tareas propia y global.</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <CheckSquare class="h-4 w-4 text-[#f53003] dark:text-[#FF4433]"/>
                            <span>Filtros y CRUD implementados con arquitectura C-S-R.</span>
                        </li>
                    </ul>
                    
                    <Link
                        :href="login()"
                        class="inline-block rounded-md bg-[#1b1b18] px-6 py-2 text-sm font-semibold text-white shadow-md hover:bg-black dark:bg-[#EDEDEC] dark:text-[#1b1b18] dark:hover:bg-white"
                    >
                        Empezar ahora
                    </Link>
                </div>
                
                <div
                    class="relative aspect-video w-full shrink-0 overflow-hidden rounded-b-lg bg-[#fff2f2] lg:aspect-auto lg:w-1/3 lg:rounded-r-lg lg:rounded-b-none dark:bg-[#1D0002]"
                >
                    <div class="flex h-full items-center justify-center p-8">
                        <ListTodo class="h-20 w-20 text-[#f53003] dark:text-[#FF4433]" />
                    </div>
                </div>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>