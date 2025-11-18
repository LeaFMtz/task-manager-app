# 💾 [Prueba Técnica] Task Manager: Gestión de Tareas y Roles (Laravel 12 + Vue 3)

Este proyecto es la implementación completa de un Sistema de Gestión de Tareas con Control de Acceso basado en Roles (RBAC), cumpliendo con todos los requisitos funcionales y de seguridad solicitados para la prueba técnica.

**Autor:** Leandro Fabian Martinez

---

## 🛠 Stack y Arquitectura

Hemos optado por una arquitectura robusta y testeable, priorizando la **Separación de Responsabilidades (SRP)** en todas las capas, un enfoque que garantiza la escalabilidad y la facilidad de mantenimiento.

| Capa | Tecnología | Patrón Principal |
| :--- | :--- | :--- |
| **Backend** | Laravel 12 (API) | **C-S-R (Controller-Service-Repository)** para toda la lógica de negocio. |
| **Seguridad** | Spatie/laravel-permission | Control granular de roles y permisos en BD y blindaje por FormRequests. |
| **Frontend** | Vue 3 + Inertia.js + TypeScript | Patrón **API-Driven** (Axios) para mutaciones (CRUD) y **Router Inertia** para recargas parciales. |
| **Estilos/UI** | Tailwind CSS / Shadcn-vue | Diseño modular, consistente y responsivo. |

---

## ✅ Características y Requisitos Cumplidos

### Funcionalidad de Negocio

* **Sistema de Roles Funcional:** Implementación completa con Spatie (Roles: `admin`, `editor`, `viewer`).
* **CRUD de Tareas Seguro:** Creación, lectura, actualización y eliminación de tareas protegidas por permisos granulares (propio vs. todos).
* **Filtros Dinámicos:** Filtrado de tareas por estado (`pending`, `completed`, `in_progress`) sin recarga de página.
* **Gestión de Usuarios (Panel de Admin):** Vista blindada (`/users`) para listar y actualizar roles de otros usuarios, cumpliendo el requisito de administración.



## 🚀 Instalación y Pruebas

Sigue estos pasos en tu terminal para clonar y levantar el proyecto:

1.  **Clonar el repositorio:**
    ```bash
    git clone YOUR_GITHUB_URL
    cd laravel-task-manager-app 
    ```

2.  **Configuración de Backend:**
    ```bash
    # Instalar dependencias de PHP
    composer install 

    # Crear archivo .env y generar key
    cp .env.example .env
    php artisan key:generate

    # ❗ ACCIÓN CRÍTICA: Crea una base de datos vacía y configura su acceso en el archivo .env

    # Ejecutar migraciones y seeders (Crea roles, permisos y usuarios de prueba)
    php artisan migrate:fresh --seed 
    ```

3.  **Configuración de Frontend:**
    ```bash
    # Instalar dependencias de Node.js
    npm install 

    # Compilar y correr el servidor de desarrollo (Comando específico del proyecto)
    composer run dev
    ```
4.  **Acceso a la Aplicación:**
    * Abre tu navegador en la URL local de Laravel.
    * **URLs Clave para Probar:**
        * `/login` (Punto de acceso)
        * `/dashboard` (Tareas Propias)
        * `/all-tasks` (Solo Admin)
        * `/users` (Solo Admin)

---

## 👤 Usuarios de Prueba

Puedes acceder con cualquiera de las siguientes credenciales (creadas con el seeder):

| Rol | Email | Contraseña | Permisos Clave |
| :--- | :--- | :--- | :--- |
| **admin** | `admin@test.com` | `password` | **CRUD Total** y Gestión de Usuarios. |
| **editor** | `editor@test.com` | `password` | CRUD solo sobre **sus propias tareas**. |
| **viewer** | `viewer@test.com` | `password` | Solo **visualización** de sus propias tareas. |