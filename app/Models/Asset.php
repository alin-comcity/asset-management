<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_tag',
        'asset_photo',
        'asset_name',
        'serial',
        'model_name',
        'status_label',
        'condition_of_asset',
        'employee_id',
        'assigned_to',
        'depertment',
        'location',
        'company',
        'received_date',
        'quantity',
        'notes'
    ];
}
