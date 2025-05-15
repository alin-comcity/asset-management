<?php

namespace App\Filament\Resources\AssetManagementResource\Pages;

use App\Models\AssetManagement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\AssetManagementResource;

class CreateAssetManagement extends CreateRecord
{
    protected static string $resource = AssetManagementResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord(); // নতুন asset_management রেকর্ড

        $selectedAssets = $this->form->getRawState()['assets'] ?? [];

        foreach ($selectedAssets as $assetId) {
            // get all existing data of asset_management which is related to this asset_id (except preset)
            $oldManagements = DB::table('asset_asset_management')
                ->where('asset_id', $assetId)
                ->where('asset_management_id', '!=', $record->id)
                ->pluck('asset_management_id');

            if ($oldManagements->isNotEmpty()) {
                // delete old existing pivot records 
                DB::table('asset_asset_management')
                    ->where('asset_id', $assetId)
                    ->whereIn('asset_management_id', $oldManagements)
                    ->delete();

                // আগের মূল asset_management রেকর্ডগুলোও ডিলিট
                // AssetManagement::whereIn('id', $oldManagements)->delete();
            }
        }

        // সবশেষে নতুন রিলেশন সংযুক্ত করা
        $record->assets()->sync($selectedAssets);
    }
}
