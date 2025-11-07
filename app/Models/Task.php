<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    public static function statuses(): array
    {
        return [self::STATUS_PENDING, self::STATUS_IN_PROGRESS, self::STATUS_COMPLETED];
    }

    // Priority constants
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';

    public static function priorities(): array
    {
        return [self::PRIORITY_LOW, self::PRIORITY_MEDIUM, self::PRIORITY_HIGH];
    }

    /**
     * Task belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by priority
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope to order by due date
     */
    public function scopeOrderByDueDate($query)
    {
        return $query->orderBy('due_date', 'asc');
    }
}
