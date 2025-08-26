<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WasteTypeResource\Pages;
use App\Filament\Admin\Resources\WasteTypeResource\RelationManagers;
use App\Models\WasteType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WasteTypeResource extends Resource
{
    protected static ?string $model = WasteType::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box';
    protected static ?string $navigationLabel = 'Jenis Sampah';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'TPS';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('waste_description')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {   
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type_name')->searchable()
                ->sortable()
                ->label('Jenis Sampah'),
                // ->description(fn (WasteType $record): string => $record->waste_description),
                Tables\Columns\TextColumn::make('waste_description')
                ->label('Deskripsi Sampah'),
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
            ])
            ->searchPlaceholder('Search (Jenis Sampah)')
            ->searchOnBlur();
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
            'index' => Pages\ListWasteTypes::route('/'),
            'create' => Pages\CreateWasteType::route('/create'),
            'edit' => Pages\EditWasteType::route('/{record}/edit'),
        ];
    }
}
