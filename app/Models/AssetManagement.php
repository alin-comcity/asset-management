<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetManagement extends Model
{
    protected $table = "asset_management";

    protected $fillable = [
        'cat_id',
        'emp_id',
        'status',
        'assign_date'
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

    public function asset()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }

    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_cat_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'cat_id');
    }

    public function assets()
    {
        return $this->belongsToMany(Assets::class, 'asset_asset_management', 'asset_management_id', 'asset_id');
    }
}
