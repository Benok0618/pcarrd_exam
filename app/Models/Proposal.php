<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_title',
        'category_id',
        'status',
        'description',
        'objective',
        'attachment',
        'start_date',
        'end_date',
        'est_budget',
        'created_by',
        'project_lead',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'est_budget' => 'integer',
            'attachment' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function projectLead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_lead');
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(Timeline::class, 'project_id')->latest();
    }
}
