<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetManagement extends Model
{
    protected $fillable = [
        'asset_id',
        'emp_id',
        'asset_cat_id'
    ];

    public function assetList(): HasMany
    {
        return $this->hasMany(Assets::class);
    }

    public function empList(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function assetCat(): HasMany
    {
        return $this->hasMany(AssetCategory::class);
    }
}
