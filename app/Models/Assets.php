<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    //
    public function assetManagements()
    {
        return $this->belongsToMany(AssetManagement::class, 'asset_asset_managements', 'asset_id', 'asset_management_id');
    }
}
