<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Filament\Admin\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists; // <-- Jangan lupa import
use FIlament\Infolists\Infolist;
use Filament\Resources\Pages\ListRecords;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-text';
    protected static ?string $navigationLabel = 'Laporan TPS';
    protected static ?int $navigationSort = 1;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(100),
                    Forms\Components\Textarea::make('address')
                    ->required(),
                    Forms\Components\TextInput::make('status')
                        ->required()
                        ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\ImageColumn::make('image')
                //     ->width(100)
                //     ->height(100),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()
                ->copyable()
                ->copyMessage('Email address copied')
                ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('address')->searchable()
                ->wrap(),
                Tables\Columns\TextColumn::make('status')
                ->color('primary'),
                Tables\Columns\TextColumn::make('waktu_lapor')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('status')
                    ->label('Status')
                    ->query(fn ($query) => $query->where('status', 'pending')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\ExportAction::make(),
                // Tables\Actions\EditAction::make(),

                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\ImageEntry::make('image') // 🖼️ Tampilkan gambar di sini
                    ->width(400)
                    ->height('auto'),
                Infolists\Components\TextEntry::make('address'),
                Infolists\Components\TextEntry::make('status'),
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
            'index' => Pages\ListReports::route('/'),
            // 'view' => ListRecords::route('/{record}'),
            // 'create' => Pages\CreateReport::route('/create'),
            // 'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
}
