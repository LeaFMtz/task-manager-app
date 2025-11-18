<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    
    public function getAllUsersForManagement(int $currentUserId): Collection
    {
        return User::with('roles')
            ->where('id', '!=', $currentUserId)
            ->select(['id', 'name', 'email', 'created_at'])
            ->get();
    }

    
    public function findById(int $id): User
    {
        return User::findOrFail($id);
    }

    
    public function updateRole(User $user, string $roleName): void
    {
        $user->syncRoles([$roleName]);
    }
    
    public function delete(User $user): void
    {
        $user->delete();
    }
}