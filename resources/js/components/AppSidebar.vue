<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, ListTodo, BookUser } from 'lucide-vue-next';

const page = usePage();

const userRoles = computed(() => page.props.auth.user_roles || []);
const userPermissions = computed(() => page.props.auth.user_permissions || []);
const tasksAll = () => {
    return '/all-tasks'; 
};

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'My Tasks',
            href: dashboard(),
            icon: LayoutGrid,
        },
    ];

    if (userPermissions.value.includes('task-view-all')) {
        items.push({
            title: 'All Tasks',
            href: tasksAll(),
            icon: ListTodo,
        });
    }

    if (userRoles.value.includes('admin')) {
        items.push({
            title: 'Manage Users',
            href: '/users',
            icon: BookUser,
        });
    }

    return items;
});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <span class="p-2 text-lg font-semibold text-foreground">
                                Task Manager
                            </span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>