<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AssetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetResource\RelationManagers;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('asset_photo')
                    ->label('Image')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->openable(),
                Forms\Components\TextInput::make('asset_tag')
                    ->label('Asset Tag')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('asset_name')
                    ->label('Asset Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('serial')
                    ->label('Serial')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model_name')
                    ->label('Model')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status_label')
                    ->label('Status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('condition_of_asset')
                    ->options([
                        'new' => 'New',
                        'used' => 'Used'
                    ])
                    ->label('Condition')
                    ->required(),
                Forms\Components\TextInput::make('employee_id')
                    ->label('Employee ID')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('assigned_to')
                    ->label('Assigned To')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('depertment')
                    ->label('Depertment')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->label('Location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company')
                    ->label('Company')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('received_date')
                    ->label('Received Date')
                    ->required(),
                Forms\Components\TextInput::make('notes')
                    ->label('Note')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('asset_photo')
                    ->circular()
                    ->stacked()
                    ->label('Image')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('Asset Tag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Asset Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial')
                    ->label('Serial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_name')
                    ->label('Model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('condition_of_asset')
                    ->label('Condition')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->searchable(),
                Tables\Columns\TextColumn::make('employee_id')
                    ->label('Employee ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->label('Assigned To')
                    ->searchable(),
                Tables\Columns\TextColumn::make('depertment')
                    ->label('Depertment')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company')
                    ->label('Company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('received_date')
                    ->label('Received Date')
                    ->dateTime('F j, Y, g:i a')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Note')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
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
                SelectFilter::make('employee_id')
                    ->label('Employee')
                    ->options(
                        fn() => \App\Models\Asset::query()
                            ->select('employee_id')
                            ->distinct()
                            ->pluck('employee_id', 'employee_id')
                    ),
                SelectFilter::make('condition_of_asset')
                    ->label('Condition Of Asset')
                    ->options(
                        fn() => \App\Models\Asset::query()
                            ->select('condition_of_asset')
                            ->distinct()
                            ->pluck('condition_of_asset', 'condition_of_asset')
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
