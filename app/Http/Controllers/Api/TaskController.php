<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\TaskService;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Access\AuthorizationException;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): Response | JsonResponse
    {
        try {
            $status = $request->query('status');
            $tasks = $this->taskService->index($status);

            return Inertia::render('Dashboard', [
                'tasks' => $tasks
            ]);
        } catch (AuthorizationException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->store($request->validated());
        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        try {
            $user = Auth::user();
            if ($user->can('task-view-all') || ($user->can('task-view-own') && $task->user_id === $user->id)) {
                return response()->json($task->load('user'));
            }
            throw new AuthorizationException('No tienes permiso para ver esta tarea.');
        } catch (AuthorizationException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updatedTask = $this->taskService->update($request->validated(), $task);
        return response()->json($updatedTask);
    }

    public function destroy(Task $task): JsonResponse
    {
        try {
            $this->taskService->destroy($task);
            return response()->json(null, 204);
        } catch (AuthorizationException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }
    public function allTasks(Request $request): Response | JsonResponse
    {
        try {
            $status = $request->query('status');
            $tasks = $this->taskService->getTasksForAdmin($status);

            return Inertia::render('Admin/AllTasks', [
                'tasks' => $tasks
            ]);
        } catch (AuthorizationException $exception) {
            return response()->json(['message' => $exception->getMessage()], 403);
        }
    }
}
