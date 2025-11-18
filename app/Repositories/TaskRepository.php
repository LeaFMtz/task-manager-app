<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function getAllTasks(?string $status = null): Collection
    {
        return Task::with('user')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();
    }

    public function getTasksForUser(User $user, ?string $status = null): Collection
    {
        return $user->tasks()->with('user')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->get();
    }

    public function createTask(User $user, array $data): Task
    {
        return $user->tasks()->create($data);
    }

    public function findTaskById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task;
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}