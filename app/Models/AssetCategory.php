<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetCategory extends Model
{
    protected $fillable = [
        'cat_name',
        'cat_desc',
        'cat_slug'
    ];

    public function asset(): HasMany
    {
        return $this->hasMany(Assets::class, 'asset_cat_id');
    }
}
