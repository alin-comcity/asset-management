<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Assets;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AssetManagement;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetManagementResource\Pages;
use App\Filament\Resources\AssetManagementResource\RelationManagers;

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

                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    ->options(function (callable $get) {
                        $categoryId = $get('asset_cat_id');

                        if (!$categoryId) {
                            return [];
                        }

                        return Assets::where('asset_cat_id', $categoryId)->pluck('asset_name', 'id');
                    })
                    ->searchable()
                    ->reactive()
                    ->visible(fn(callable $get) => $get('asset_cat_id') !== null), // Show only if category selected             

                Forms\Components\Select::make('emp_id')
                    ->label('Employee All')
                    ->relationship('empList', 'emp_name')
                    ->preload()
                    ->searchable()
                    ->visible(fn(callable $get) => $get('asset_id') !== null), // Show only if asset selected

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
                Tables\Columns\TextColumn::make('asset.asset_name')
                    ->label('Asset Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.cat_name')
                    ->label('Asset Category')
                    ->searchable(),
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
