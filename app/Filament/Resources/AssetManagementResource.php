<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Assets;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AssetCategory;
use App\Models\AssetManagement;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
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
                Forms\Components\Select::make('cat_id')
                    ->label('Category')
                    ->relationship('category', 'cat_name')
                    ->preload()
                    ->searchable(),

                Forms\Components\Select::make('asset_id')
                    ->label('Asset')
                    ->relationship('assets', 'asset_name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return $record->asset_name . ' (' . $record->asset_serial . ')';
                    }),

                Forms\Components\Select::make('emp_id')
                    ->label('Employee')
                    ->relationship('employee', 'emp_name')
                    ->preload()
                    ->searchable(),

                Forms\Components\DatePicker::make('assign_date')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category2.cat_name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('employee2.emp_name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('assets.asset_name')
                    ->label('Asset Name')
                    ->listWithLineBreaks()
                    ->sortable(),

                Tables\Columns\TextColumn::make('assets.asset_type')
                    ->label('Asset Type')
                    ->listWithLineBreaks()
                    ->sortable(),

                Tables\Columns\TextColumn::make('assign_date')
                    ->date()
                    ->sortable(),
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
