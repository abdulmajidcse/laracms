<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'payload',
        'order',
        'status',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContentType::class,
            'payload' => 'array',
            'order' => 'double',
            'status' => ContentStatus::class,
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    #[Scope]
    public function search(Builder $query, ?string $keyword = null): void
    {
        if (blank($keyword)) {
            return;
        }

        $query->where(function (Builder $q) use ($keyword) {
            $like = "%{$keyword}%";

            $q->where('title', 'like', $like)
                ->orWhere('type', 'like', $like)
                ->orWhere('status', 'like', $like);
        });
    }
}
