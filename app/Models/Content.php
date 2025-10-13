<?php

namespace App\Models;

use App\ContentStatus;
use App\ContentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Content extends Model
{
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
}
