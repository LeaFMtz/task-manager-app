<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { ListTodo } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();
</script>

<template>
    <AuthBase title="Bienvenido de Nuevo" description="Ingresa tus credenciales para acceder a Task Manager">

        <Head title="Iniciar Sesión" />

        <div class="flex flex-col items-center justify-center mb-6">
            <ListTodo class="h-10 w-10 text-[#f53003] dark:text-[#FF4433] mb-2" />
            <h2 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white">Task Manager</h2>
        </div>
        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <Form v-bind="store.form()" :reset-on-success="['password']" v-slot="{ errors, processing }"
            class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input id="email" type="email" name="email" required autofocus :tabindex="1" autocomplete="email"
                        placeholder="correo@ejemplo.com" />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Contraseña</Label>
                        <TextLink v-if="canResetPassword" :href="request()"
                            class="text-sm font-medium text-[#f53003] hover:text-[#FF4433] dark:text-[#FF4433] dark:hover:text-[#f53003]"
                            :tabindex="5">
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <Input id="password" type="password" name="password" required :tabindex="2"
                        autocomplete="current-password" placeholder="Contraseña" />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" name="remember" :tabindex="3" />
                        <span>Recuérdame</span>
                    </Label>
                </div>

                <Button type="submit"
                    class="mt-4 w-full bg-[#f53003] hover:bg-[#FF4433] text-white dark:bg-[#FF4433] dark:hover:bg-[#f53003]"
                    :tabindex="4" :disabled="processing" data-test="login-button">
                    <Spinner v-if="processing" />
                    Iniciar Sesión
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground" v-if="canRegister">
                ¿No tienes una cuenta?
                <TextLink :href="register()" :tabindex="5"
                    class="font-medium text-[#f53003] hover:text-[#FF4433] dark:text-[#FF4433] dark:hover:text-[#f53003]">
                    Regístrate
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>