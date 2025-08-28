<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Form;
use Filament\Tables\Table;
use FIlament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists; // <-- Jangan lupa import
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Filament\Admin\Resources\ReportResource\RelationManagers;

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
                    ->maxLength(100)
                    ->disabled(),
                Forms\Components\TextInput::make('username')
                    // ->required()
                    ->maxLength(100)
                    ->disabled(),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->maxLength(100)
                    ->disabled(),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->disabled(),
                TextInput::make('latitude'),
                TextInput::make('longitude'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ]),
                FileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('username')->label('Username')->searchable(),
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        // Ambil semua nilai unik dari kolom 'tps_kecamatan' dan jadikan pilihan
                        Report::query()->distinct()->pluck('status', 'status')->all()
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
                Infolists\Components\TextEntry::make('username'),
                Infolists\Components\TextEntry::make('email')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500),
                Infolists\Components\TextEntry::make('address'),
                Infolists\Components\TextEntry::make('status'),
                Infolists\Components\ImageEntry::make('image') // 🖼️ Tampilkan gambar di sini
                    ->width(400)
                    ->height('auto'),
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
            'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
    
}
