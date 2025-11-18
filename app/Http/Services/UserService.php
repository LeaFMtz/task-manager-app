<?php

namespace App\Http\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): Collection
    {
        return $this->userRepository->getAllUsersForManagement(Auth::id());
    }

    public function updateRole(int $userId, string $roleName): void
    {
        $user = $this->userRepository->findById($userId);
    
        if (Auth::id() === $user->id) {
            throw new AuthorizationException('No puedes cambiar tu propio rol.');
        }

        $this->userRepository->updateRole($user, $roleName);
    }
    
    public function destroy(int $userId): void
    {
        $user = $this->userRepository->findById($userId);

        if (Auth::id() === $user->id) {
            throw new AuthorizationException('No puedes eliminar tu propia cuenta.');
        }

        $this->userRepository->delete($user);
    }
}