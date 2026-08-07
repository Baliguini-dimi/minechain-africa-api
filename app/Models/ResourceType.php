<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceType extends Model
{
    protected $fillable = [
        'organization_id',
        'name',
        'unit_of_measure',
        'density',
        'weighing_method',
        'quality_categories',
        'required_documents',
    ];

    protected $casts = [
        'density' => 'decimal:4',
        'quality_categories' => 'array',
        'required_documents' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }
}