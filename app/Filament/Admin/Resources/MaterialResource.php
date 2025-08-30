<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use App\Models\WasteType;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\MaterialResource\Pages;
use App\Filament\Admin\Resources\MaterialResource\RelationManagers;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('waste_types_id') // Gunakan nama foreign key column
                    ->relationship('waste_types', 'type_name') // (nama relasi, nama kolom yang ditampilkan)
                    ->label('Nama Jenis Limbah')
                    ->searchable()
                    ->preload() // Penting untuk performa jika data TPS banyak
                    ->required(),
                Forms\Components\TextInput::make('description_mat')
                ->label('Deskripsi Materi'),
                Forms\Components\TextInput::make('recycle_info')
                ->label('Info Daur Ulang'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('waste_types.type_name')
                ->label('Jenis Sampah')->searchable(),
                TextColumn::make('description_mat')->label('Deskripsi Materi')
                ->wrap()
                // ->words(4)
                ->searchable()
                ->sortable(),
                TextColumn::make('recycle_info')->label('Info Daur Ulang')
                ->wrap()
                // ->words(4)
                ->searchable()
                ->sortable(),
            ])
            ->filters([
                
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
