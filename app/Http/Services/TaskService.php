<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasksForAdmin(?string $status = null)
    {
        return $this->taskRepository->getAllTasks($status);
    }

    public function index(?string $status = null)
    {
        $user = Auth::user();

        if ($user->can('task-view-own')) {
            return $this->taskRepository->getTasksForUser($user, $status);
        }

        throw new AuthorizationException('No tienes permiso para ver tareas.');
    }

    public function store(array $data): Task
    {
        $user = Auth::user();

        if (!$user->can('task-create')) {
            throw new AuthorizationException('No tienes permiso para crear tareas.');
        }

        return $this->taskRepository->createTask($user, $data);
    }

    public function update(array $data, Task $task): Task
    {
        $user = Auth::user();

        if ($user->can('task-update-all')) {
            return $this->taskRepository->updateTask($task, $data);
        }

        if ($user->can('task-update-own')) {
            if ($task->user_id !== $user->id) {
                throw new AuthorizationException('Solo puedes editar tus propias tareas.');
            }
            return $this->taskRepository->updateTask($task, $data);
        }

        throw new AuthorizationException('No tienes permiso para editar esta tarea.');
    }

    public function destroy(Task $task): void
    {
        $user = Auth::user();

        if ($user->can('task-delete-all')) {
            $this->taskRepository->deleteTask($task);
            return;
        }

        if ($user->can('task-delete-own')) {
            if ($task->user_id !== $user->id) {
                throw new AuthorizationException('Solo puedes eliminar tus propias tareas.');
            }
            $this->taskRepository->deleteTask($task);
            return;
        }

        throw new AuthorizationException('No tienes permiso para eliminar esta tarea.');
    }
}
