<?php

namespace App\Filament\Resources\AssetManagementResource\Pages;

use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AssetManagementResource;

class EditAssetManagement extends EditRecord
{
    protected static string $resource = AssetManagementResource::class;

    protected function afterSave(): void {}
}
