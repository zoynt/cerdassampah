<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TpsResource\Pages;
use App\Filament\Admin\Resources\TpsResource\RelationManagers;
use App\Models\Tps;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists; // <-- Jangan lupa import
use FIlament\Infolists\Infolist;


use function Laravel\Prompts\form;

class TpsResource extends Resource
{
    protected static ?string $model = Tps::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'TPS';
    protected static ?string $navigationGroup = 'Lokasi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tps_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tps_longitude')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tps_latitude')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('kecamatan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tps_status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('tps_description')
                    ->required()
                    ->maxLength(300),
                Forms\Components\TextInput::make('tps_day')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tps_start_time')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tps_end_time')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tps_transport')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tps_name')->searchable(),
                Tables\Columns\TextColumn::make('tps_longitude'),
                Tables\Columns\TextColumn::make('tps_latitude'),
                Tables\Columns\TextColumn::make('kecamatan')->searchable(),
                Tables\Columns\TextColumn::make('tps_status')->searchable(),
                Tables\Columns\TextColumn::make('tps_description'),
                Tables\Columns\TextColumn::make('tps_day')->searchable(),
                Tables\Columns\TextColumn::make('tps_start_time')->searchable(),
                Tables\Columns\TextColumn::make('tps_end_time')->searchable(),
                Tables\Columns\TextColumn::make('tps_transport')->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('kecamatan')
                    ->label('kecamatan')
                    ->query(fn ($query) => $query->where('kecamatan', 'banjarmasin utara')),

            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

        public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('tps_name'),
                Infolists\Components\TextEntry::make('tps_longitude'),
                Infolists\Components\TextEntry::make('tps_latitude'),
                Infolists\Components\TextEntry::make('kecamatan'),
                Infolists\Components\TextEntry::make('tps_status'),
                Infolists\Components\TextEntry::make('tps_description'),
                Infolists\Components\TextEntry::make('tps_day'),
                Infolists\Components\TextEntry::make('tps_start_time'),
                Infolists\Components\TextEntry::make('tps_end_time'),
                Infolists\Components\TextEntry::make('tps_transport'),
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
            'index' => Pages\ListTps::route('/'),
            'create' => Pages\CreateTps::route('/create'),
            'edit' => Pages\EditTps::route('/{record}/edit'),
        ];
    }
}
