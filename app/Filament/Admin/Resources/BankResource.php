<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Bank;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BankResource\Pages;
use App\Filament\Admin\Resources\BankResource\RelationManagers;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Lokasi';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Bank Sampah';
    protected static ?string $pluralModelLabel = 'Bank Sampah'; // Nama di semua tempat




    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('bank_name'),
                TextInput::make('bank_longitude'),
                TextInput::make('bank_latitude'),
                TextInput::make('kecamatan'),
                TextInput::make('bank_day'),
                TextInput::make('bank_start_time'),
                TextInput::make('bank_end_time'),
                TextInput::make('bank_description'),
                FileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->Label('Gambar Bank'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank_name')->searchable(),
                TextColumn::make('kecamatan')->searchable(),
                TextColumn::make('bank_day')->searchable(),
                TextColumn::make('bank_start_time')->searchable(),
                TextColumn::make('bank_end_time')->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->label('kecamatan')
                    ->options(
                        // Ambil semua nilai unik dari kolom 'bank_kecamatan' dan jadikan pilihan
                        Bank::query()->distinct()->pluck('kecamatan', 'kecamatan')->all()
                    )
                
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
            'index' => Pages\ListBanks::route('/'),
            'create' => Pages\CreateBank::route('/create'),
            // 'edit' => Pages\EditBank::route('/{record}/edit'),
        ];
    }
}
