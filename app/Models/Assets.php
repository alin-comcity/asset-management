<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assets extends Model
{
    protected $fillable = [
        'asset_name',
        'asset_cat_id',
        'asset_desc',
        'asset_tag',
        'asset_serial',
        'asset_type',
        'purchase_date',
        'asset_image'
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_cat_id');
    }

    public function employee(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class);
    }

    public function assetmanagement(): BelongsToMany
    {
        return $this->belongsToMany(AssetManagement::class, 'asset_asset_management', 'asset_management_id', 'asset_id');
    }
}
