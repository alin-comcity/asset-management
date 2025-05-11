<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    protected $fillable = [
        'emp_id',
        'emp_name',
        'emp_email'
    ];

    public function asset(): BelongsToMany
    {
        return $this->belongsToMany(Assets::class);
    }

    public function assetmanagement(): BelongsToMany
    {
        return $this->belongsToMany(AssetManagement::class);
    }
}
