<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use App\Models\Assets;
use App\Models\AssetManagement;


class AssetManagementController extends Controller
{
    public function index()
    {
        // $test = Assets::with('assetmanagement')->find(1);
        // return $test;
        $test = AssetCategory::with('asset')->find(5);
        return $test;
        // $test = AssetManagement::with('assets')->find(14);
        // return $test;
    }

    public function create()
    {
        AssetManagement::create([
            'cat_id' => 1,
            'emp_id' => 1,
            'assign_date' => '2025-05-10'
        ])->assets()->sync([2, 5]);
    }
}
