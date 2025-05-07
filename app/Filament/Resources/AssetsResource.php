<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Assets;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AssetCategory;
use Filament\Resources\Resource;
use Filament\Support\Assets\Asset;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AssetsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetsResource\RelationManagers;
use Filament\Forms\Get;

class AssetsResource extends Resource
{
    protected static ?string $model = Assets::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('asset_name')
                    ->label('Asset Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('asset_cat_id')
                    ->label('Category')
                    ->relationship(name: 'categories', titleAttribute: 'cat_name')
                    ->preload()
                    ->live()
                    ->searchable()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            // Example: Set asset_tag based on category ID (can use name too)
                            $prefix = 'CAT-' . str_pad($state, 2, '0', STR_PAD_LEFT);
                            $set('asset_tag', $prefix . '-' . now()->format('His')); // CAT-01-142533
                        }
                    }),

                Forms\Components\Textarea::make('asset_desc')
                    ->label('Asset Description')
                    ->rows(10)
                    ->cols(20),

                Forms\Components\TextInput::make('asset_tag')
                    ->label('Asset Tag')
                    ->required()
                    ->reactive(),
                // ->disabled()
                // ->dehydrated()
                // ->default(function () {
                //     // Auto increment id. ex. 'CCTL-001', 'CCTL-002' 
                //     $cat =  AssetCategory::where('asset_tag', 'like', 'CCTL-' . '%')
                //         ->orderByDesc('id')
                //         ->first();
                //     $last = Assets::where('asset_tag', 'like', 'CCTL-' . '%')
                //         ->orderByDesc('id')
                //         ->first();
                //     $next = 1;
                //     if ($last && preg_match('/-(\d{4})$/', $last->asset_tag, $matches)) {
                //         $next = (int) $matches[1] + 1;
                //     }
                //     return 'CCTL-' . str_pad($next, 4, '0', STR_PAD_LEFT);
                // }),



                Forms\Components\TextInput::make('asset_serial')
                    ->label('Asset Serial')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('asset_type')
                    ->options([
                        'new' => 'New',
                        'used' => 'Used',
                        'borrowed' => 'Borrowed',
                    ])
                    ->label('Asset Type')
                    ->required(),
                Forms\Components\DateTimePicker::make('purchase_date')
                    ->label('Purchase Date')
                    ->required(),
                Forms\Components\FileUpload::make('asset_image')
                    ->label('Asset Image')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->openable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Asset Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_cat_id')
                    ->label('Category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_desc')
                    ->label('Asset Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('Asset Tag')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_serial')
                    ->label('Asset Serial')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asset_type')
                    ->label('Asset Type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Purchase Date')
                    ->dateTime('F j, Y, g:i a')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('asset_image')
                    ->circular()
                    ->stacked()
                    ->label('Asset Image')
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'create' => Pages\CreateAssets::route('/create'),
            'edit' => Pages\EditAssets::route('/{record}/edit'),
        ];
    }
}
