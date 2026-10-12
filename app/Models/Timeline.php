<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timeline extends Model
{
    protected $fillable = [
        'project_id',
        'activity',
        'updated_by',
        'updated_to',
        'remarks',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'project_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
