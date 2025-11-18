<?php

namespace App\Http\Controllers\Api;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UpdateUserRoleRequest; 
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    // CAMBIO: Inyectamos el Servicio
    public function __construct(UserService $userService)
    {
        $this->userService = $userService; 
    }

    /**
     * Muestra la lista de usuarios para la gestión (Admin).
     */
    public function index(): Response
    {
        $users = $this->userService->index();
        $roles = Role::pluck('name');

        return Inertia::render('Admin/UserManagement', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
    
    /**
     * Actualiza el rol de un usuario (Endpoint API).
     */
    public function update(UpdateUserRoleRequest $request, User $user): JsonResponse
    {
        try {
            $this->userService->updateRole($user->id, $request->input('role_name'));
            return response()->json(['message' => 'Rol de usuario actualizado correctamente.'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
    
    /**
     * Elimina un usuario (Endpoint API).
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $this->userService->destroy($user->id);
            return response()->json(null, 204);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}