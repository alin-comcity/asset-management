<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function categories(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class);
    }
}
