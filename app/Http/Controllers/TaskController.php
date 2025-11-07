<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $perPage = $request->query('per_page', 15);

        $tasks = $this->taskService->getPaginatedUserTasks($user, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks->items(),
            'pagination' => [
                'total' => $tasks->total(),
                'per_page' => $tasks->perPage(),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'next_page_url' => $tasks->nextPageUrl(),
                'prev_page_url' => $tasks->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:' . implode(',', Task::statuses()),
            'priority' => 'required|in:' . implode(',', Task::priorities()),
            'due_date' => 'nullable|date_format:Y-m-d',
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $task = $this->taskService->createTask($user, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $task = $this->taskService->getTask($user, $id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $task,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:' . implode(',', Task::statuses()),
            'priority' => 'sometimes|in:' . implode(',', Task::priorities()),
            'due_date' => 'nullable|date_format:Y-m-d',
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $task = $this->taskService->updateTask($user, $id, $validated);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or unauthorized',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $deleted = $this->taskService->deleteTask($user, $id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or unauthorized',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ], 200);
    }

    /**
     * Get filtered tasks by status and priority
     * GET /api/tasks/filter?status=pending&priority=high
     */
    public function filter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'nullable|in:' . implode(',', Task::statuses()),
            'priority' => 'nullable|in:' . implode(',', Task::priorities()),
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $tasks = $this->taskService->getUserTasks(
            $user,
            $validated['status'] ?? null,
            $validated['priority'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Filtered tasks retrieved successfully',
            'data' => $tasks,
        ], 200);
    }

    /**
     * Get upcoming tasks
     * GET /api/tasks/upcoming?days=7
     */
    public function upcoming(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'days' => 'nullable|integer|min:1|max:90',
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $days = $validated['days'] ?? 7;
        $tasks = $this->taskService->getUpcomingTasks($user, $days);

        return response()->json([
            'success' => true,
            'message' => 'Upcoming tasks retrieved successfully',
            'data' => $tasks,
        ], 200);
    }

    /**
     * Get task statistics
     * GET /api/tasks/statistics
     */
    public function statistics(): JsonResponse
    {
        $user = JWTAuth::parseToken()->authenticate();
        $stats = $this->taskService->getTaskStatistics($user);

        return response()->json([
            'success' => true,
            'message' => 'Task statistics retrieved successfully',
            'data' => $stats,
        ], 200);
    }

    /**
     * Change task status
     * PATCH /api/tasks/{id}/status
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Task::statuses()),
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $task = $this->taskService->changeTaskStatus($user, $id, $validated['status']);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found or unauthorized',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'data' => $task,
        ], 200);
    }

    /**
     * Bulk update task statuses
     * PATCH /api/tasks/bulk-status
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'task_ids' => 'required|array|min:1',
            'task_ids.*' => 'integer',
            'status' => 'required|in:' . implode(',', Task::statuses()),
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $count = $this->taskService->bulkUpdateTaskStatuses(
            $user,
            $validated['task_ids'],
            $validated['status']
        );

        return response()->json([
            'success' => true,
            'message' => "Updated $count task(s) successfully",
            'data' => ['updated_count' => $count],
        ], 200);
    }
}
