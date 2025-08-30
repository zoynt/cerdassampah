<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Report;
use Filament\Forms\Form;
use Filament\Tables\Table;
use FIlament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists; // <-- Jangan lupa import
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\ReportResource\Pages;
use App\Filament\Admin\Resources\ReportResource\RelationManagers;
use DateTime;

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
                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ]),
                TextInput::make('latitude')
                    ->disabled(),
                TextInput::make('longitude')
                    ->disabled(),
                Forms\Components\Textarea::make('address')
                        ->required()
                        ->autosize()
                        ->disabled(),
                Placeholder::make('image_preview')
                    ->label('Gambar Saat Ini')
                    ->content(function ($record): ?HtmlString {
                        // Cek jika record dan field 'image' ada isinya
                        if ($record && $record->image) {
                            // Dapatkan URL gambar dari disk penyimpanan Anda
                            $url = Storage::disk('public')->url($record->image);
                            
                            // Tampilkan gambar menggunakan tag <img>
                            return new HtmlString("<img src='{$url}' style='max-height: 200px;' alt='preview' />");
                        }

                        // Tampilkan null jika tidak ada gambar, agar placeholder tidak muncul
                        return null;
                    })
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
                ->wrap()
                ->limit(30),
                Tables\Columns\TextColumn::make('status')
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'gray',
                    'proses' => 'warning',
                    'selesai' => 'success',
                    'rejected' => 'danger',
                }),
                Tables\Columns\TextColumn::make('waktu_lapor')->sortable()
                ->dateTime('d M Y'),
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
            // 'edit' => Pages\EditReport::route('/{record}/edit'),
        ];
    }
    
}
