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
use Laravel\Pail\ValueObjects\Origin\Console;

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
                    ->relationship('category', 'cat_name')
                    ->preload()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) { //after select any category item
                        if ($state) {
                            $slug = AssetCategory::find($state);
                            $last = Assets::where('asset_tag', 'like', 'CCTL-' . $slug->cat_slug . '%')
                                ->orderByDesc('id')
                                ->first();


                            $remve_string = $last && preg_match('/(\d+)$/', $last->asset_tag, $matches);
                            if ($remve_string) {
                                $number = $matches[1];
                                $number++;
                            } else {
                                $number = 1;
                            }


                            if ($last) {
                                $assetTag = 'CCTL-' . strtoupper($slug->cat_slug) . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
                                $set('asset_tag', $assetTag);
                            } else {
                                $assetTag = 'CCTL-' . strtoupper($slug->cat_slug) . '-' . $number;
                                $set('asset_tag', $assetTag);
                            }
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
                // Tables\Columns\TextColumn::make('asset_cat_id')
                Tables\Columns\TextColumn::make('category.cat_name')
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
