<?php

namespace App\Filament\Admin\Resources;

use App\Models\Tps;
use Filament\Forms;
use Filament\Tables;
use App\Models\Surung;
use Filament\Forms\Form;
use Filament\Tables\Table;
use FIlament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\SurungExporter;
use App\Filament\Imports\SurungImporter;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Infolists; // <-- Jangan lupa import
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\SurungResource\Pages;
use App\Filament\Admin\Resources\SurungResource\RelationManagers;

class SurungResource extends Resource
{
    protected static ?string $model = Surung::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Lokasi Pengelola Sampah';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Surung Sintak';
    protected static ?string $pluralModelLabel = 'Surung Sintak'; // Nama di semua tempat

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tps_id') // Gunakan nama foreign key column
                    ->relationship('tps', 'tps_name') // (nama relasi, nama kolom yang ditampilkan)
                    ->label('Nama TPS')
                    ->searchable()
                    ->preload() // Penting untuk performa jika data TPS banyak
                    ->required(),
                Forms\Components\TextInput::make('surung_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('surung_longitude')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('surung_latitude')
                    ->required()
                    ->maxLength(255),
                Select::make('kecamatan')
                ->label('Kecamatan')
                ->options([
                    'banjarmasin utara' => 'Banjarmasin Utara',
                    'banjarmasin selatan' => 'Banjarmasin Selatan',
                    'banjarmasin tengah' => 'Banjarmasin Tengah',
                    'banjarmasin barat' => 'Banjarmasin Barat',
                    'banjarmasin timur' => 'Banjarmasin Timur',
                ])
                ->required(),                
                Forms\Components\TextInput::make('area')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('worker_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('surung_day')
                    ->required()
                    ->maxLength(255),
                TimePicker::make('surung_start_time')
                    ->label('Waktu Mulai')
                    ->seconds(false)  // Menggunakan format HH:MM (tanpa detik)
                    ->required(),
                TimePicker::make('surung_end_time')
                    ->label('Waktu Selesai')
                    ->seconds(false)  // Menggunakan format HH:MM (tanpa detik)
                    ->required(),
                Forms\Components\Textarea::make('surung_description')
                    ->required()
                    ->maxLength(300)
                    ->columnSpanFull(),
            ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
            Tables\Actions\ExportAction::make()
                ->exporter(SurungExporter::class)
                ->label('Export Surung')
                ->icon('heroicon-o-arrow-down-tray')
                ->formats([
                    ExportFormat::Xlsx,
                ]),
                ImportAction::make()
                    ->importer(SurungImporter::class)
                    ->label('Import Surung')
                    ->icon('heroicon-o-arrow-up-tray')
            ])

            ->columns([
                Tables\Columns\TextColumn::make('tps.tps_name') // Menampilkan nama TPS
                ->label('Nama TPS') // Opsional, untuk mengganti label
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('surung_name')->searchable(),
                Tables\Columns\TextColumn::make('kecamatan')->searchable()
                ->label('Kecamatan'),
                Tables\Columns\TextColumn::make('area')->searchable()
                ->label('Area'),
                Tables\Columns\TextColumn::make('surung_day')->searchable()
                ->label('Hari Operasional'),
                TextColumn::make('surung_start_time')
                    ->label('Waktu Mulai')
                    ->dateTime('H:i'),
                TextColumn::make('surung_end_time')
                    ->label('Waktu Selesai')
                    ->dateTime('H:i'),
                // Tables\Columns\TextColumn::make('surung_description')->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->label('Kecamatan')
                    ->options(
                        // Ambil semua nilai unik dari kolom 'surung_kecamatan' dan jadikan pilihan
                        Surung::query()->distinct()->pluck('kecamatan', 'kecamatan')->all()
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('tps.tps_name'),
                Infolists\Components\TextEntry::make('surung_longitude'),
                Infolists\Components\TextEntry::make('surung_latitude'),
                Infolists\Components\TextEntry::make('kecamatan'),
                Infolists\Components\TextEntry::make('surung_status'),
                Infolists\Components\TextEntry::make('surung_description'),
                Infolists\Components\TextEntry::make('surung_day'),
                Infolists\Components\TextEntry::make('surung_start_time'),
                Infolists\Components\TextEntry::make('surung_end_time'),
                Infolists\Components\TextEntry::make('surung_transport'),
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
            'index' => Pages\ListSurungs::route('/'),
            'create' => Pages\CreateSurung::route('/create'),
            'edit' => Pages\EditSurung::route('/{record}/edit'),
        ];
    }
}
