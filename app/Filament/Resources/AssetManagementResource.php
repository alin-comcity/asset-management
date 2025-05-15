<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Assets;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AssetManagement;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use App\Filament\Resources\AssetManagementResource\Pages;

class AssetManagementResource extends Resource
{
    protected static ?string $model = AssetManagement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_cat_id')
                    ->label('Asset Category')
                    ->relationship('category', 'cat_name')
                    ->preload()
                    ->searchable()
                    ->reactive() // Trigger changes on update
                    ->afterStateUpdated(fn(callable $set) => $set('asset_id', null)), // clear asset_id on category change


                Forms\Components\Select::make('assets')
                    ->label('Assets')
                    ->relationship('assets', 'asset_name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->options(function (callable $get) {
                        $categoryId = $get('asset_cat_id');

                        if (!$categoryId) {
                            return [];
                        }

                        return Assets::where('asset_cat_id', $categoryId)
                            ->with('assetManagements.employee')
                            ->get()
                            ->mapWithKeys(function ($asset) {
                                $employeeName = optional($asset->assetManagements->first()?->employee)->emp_name ?? 'Unassigned';
                                $employeeId = optional($asset->assetManagements->first()?->employee)->emp_id ?? '';
                                return [$asset->id => "{$asset->asset_name} ({$asset->asset_serial}) - Emp: ({$employeeId}-{$employeeName})"];
                            });
                    })
                    ->reactive()
                    ->visible(fn(callable $get) => $get('asset_cat_id') !== null) // Show only if category selected             
                    ->afterStateHydrated(function ($set, $record) {
                        if ($record) {
                            $set('assets', $record->assets->pluck('id')->toArray());
                        }
                    }),



                Forms\Components\Select::make('emp_id')
                    ->label('Employee All')
                    ->relationship('empList', 'emp_name')
                    ->preload()
                    ->searchable()
                    ->visible(fn(callable $get) => $get('assets') !== null), // Show only if asset selected

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->relationship('empList', 'emp_name')
                    ->preload()
                    ->options(
                        [
                            'new' => 'New',
                            'old' => 'Old',
                            'Borrowed' => 'Borrowed'
                        ]
                    )
                    ->searchable(),

                Forms\Components\DatePicker::make('assign_date')
                    ->label('Assign Date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('assets.asset_name')
                    ->label('Asset Name')
                    ->searchable()
                    ->formatStateUsing(function ($record) {
                        return $record->assets
                            ->map(function ($asset) {
                                $categoryName = optional($asset->category)->cat_name ?? 'No Category';
                                return "{$asset->asset_name} - ({$categoryName})";
                            })
                            ->implode("<br>");
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('employee.emp_name')
                    ->label('Employee Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assign_date')
                    ->label('Assign Date'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssetManagement::route('/'),
            'create' => Pages\CreateAssetManagement::route('/create'),
            'edit' => Pages\EditAssetManagement::route('/{record}/edit'),
        ];
    }
}
