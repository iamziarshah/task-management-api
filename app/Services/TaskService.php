<?php

namespace App\Services;

use App\Models\User;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(protected TaskRepository $taskRepository) {}

    /**
     * Get all tasks for a user
     */
    public function getUserTasks(User $user, ?string $status = null, ?string $priority = null): Collection
    {
        if ($status || $priority) {
            return $this->taskRepository->getFilteredTasks($user, $status, $priority);
        }

        return $this->taskRepository->getTasksByUser($user);
    }

    /**
     * Get paginated tasks for a user
     */
    public function getPaginatedUserTasks(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $this->taskRepository->paginateUserTasks($user, $perPage);
    }

    /**
     * Get a single task with ownership verification
     */
    public function getTask(User $user, int $taskId): ?Task
    {
        $task = $this->taskRepository->find($taskId);

        // Verify ownership
        if (!$task || $task->user_id !== $user->id) {
            return null;
        }

        return $task;
    }

    /**
     * Create a new task
     */
    public function createTask(User $user, array $data): Task
    {
        $data['user_id'] = $user->id;

        return $this->taskRepository->create($data);
    }

    /**
     * Update a task with ownership verification
     */
    public function updateTask(User $user, int $taskId, array $data): ?Task
    {
        $task = $this->getTask($user, $taskId);

        if (!$task) {
            return null;
        }

        return $this->taskRepository->update($taskId, $data);
    }

    /**
     * Delete a task with ownership verification
     */
    public function deleteTask(User $user, int $taskId): bool
    {
        $task = $this->getTask($user, $taskId);

        if (!$task) {
            return false;
        }

        return $this->taskRepository->delete($taskId);
    }

    /**
     * Get upcoming tasks
     */
    public function getUpcomingTasks(User $user, int $daysAhead = 7): Collection
    {
        return $this->taskRepository->getUpcomingTasks($user, $daysAhead);
    }

    /**
     * Get task statistics
     */
    public function getTaskStatistics(User $user): array
    {
        return $this->taskRepository->getTaskStatistics($user);
    }

    /**
     * Change task status
     */
    public function changeTaskStatus(User $user, int $taskId, string $status): ?Task
    {
        if (!in_array($status, Task::statuses())) {
            return null;
        }

        return $this->updateTask($user, $taskId, ['status' => $status]);
    }

    /**
     * Bulk update task statuses
     */
    public function bulkUpdateTaskStatuses(User $user, array $taskIds, string $status): int
    {
        if (!in_array($status, Task::statuses())) {
            return 0;
        }

        return $this->taskRepository->bulkUpdateStatus($taskIds, $status, $user);
    }
}
