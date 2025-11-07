<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;

class TaskRepository extends BaseRepository
{
    public function __construct(Task $task)
    {
        parent::__construct($task);
    }

    /**
     * Define which relations to eager load
     * This prevents N+1 query problems
     */
    protected function getRelations(): array
    {
        return ['user'];
    }

    /**
     * Get all tasks for a user with eager loading
     */
    public function getTasksByUser(User $user, array $columns = ['*'])
    {
        return $this->model
            ->where('user_id', $user->id)
            ->with($this->getRelations())
            ->get($columns);
    }

    /**
     * Get paginated tasks for a user
     */
    public function paginateUserTasks(User $user, int $perPage = 15, array $columns = ['*'])
    {
        return $this->model
            ->where('user_id', $user->id)
            ->with($this->getRelations())
            ->latest('created_at')
            ->paginate($perPage, $columns);
    }

    /**
     * Get tasks by status and priority with filtering
     */
    public function getFilteredTasks(User $user, ?string $status = null, ?string $priority = null)
    {
        $query = $this->model
            ->where('user_id', $user->id)
            ->with($this->getRelations());

        if ($status) {
            $query->byStatus($status);
        }

        if ($priority) {
            $query->byPriority($priority);
        }

        return $query->orderByDueDate()->get();
    }

    /**
     * Get upcoming tasks due within specified days
     */
    public function getUpcomingTasks(User $user, int $daysAhead = 7)
    {
        return $this->model
            ->where('user_id', $user->id)
            ->where('status', '!=', Task::STATUS_COMPLETED)
            ->whereBetween('due_date', [now(), now()->addDays($daysAhead)])
            ->with($this->getRelations())
            ->orderByDueDate()
            ->get();
    }

    /**
     * Get task statistics for a user
     */
    public function getTaskStatistics(User $user)
    {
        return [
            'total' => $this->model->where('user_id', $user->id)->count(),
            'completed' => $this->model
                ->where('user_id', $user->id)
                ->where('status', Task::STATUS_COMPLETED)
                ->count(),
            'pending' => $this->model
                ->where('user_id', $user->id)
                ->where('status', Task::STATUS_PENDING)
                ->count(),
            'in_progress' => $this->model
                ->where('user_id', $user->id)
                ->where('status', Task::STATUS_IN_PROGRESS)
                ->count(),
        ];
    }

    /**
     * Bulk update task status
     */
    public function bulkUpdateStatus(array $taskIds, string $status, User $user)
    {
        return $this->model
            ->whereIn('id', $taskIds)
            ->where('user_id', $user->id)
            ->update(['status' => $status]);
    }
}
