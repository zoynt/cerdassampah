<?php

namespace App\Filament\Admin\Resources;

use App\Models\Report;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use App\Filament\Admin\Resources\ReportResource\Pages;


use App\Filament\Exports\ReportExporter; 
use Filament\Actions\Exports\Enums\ExportFormat;

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
                Forms\Components\TextInput::make('name')->required()->maxLength(100)->disabled(),
                Forms\Components\TextInput::make('username')->maxLength(100)->disabled(),
                Forms\Components\TextInput::make('email')->required()->maxLength(100)->disabled(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ]),
                TextInput::make('latitude')->disabled(),
                TextInput::make('longitude')->disabled(),
                Forms\Components\Textarea::make('address')->required()->autosize()->disabled(),
                
                Forms\Components\Placeholder::make('image_view')
                    ->label('Bukti Foto')
                    ->columnSpanFull()
                    ->content(function (?Report $record): ?HtmlString {
                        if (!$record || empty($record->image)) {
                            return new HtmlString('<span class="text-gray-500 italic">Tidak ada gambar</span>');
                        }
                        $url = asset('storage/' . $record->image);
                        return new HtmlString("
                            <div style='margin-top: 8px;'>
                                <a href='{$url}' target='_blank'>
                                    <img src='{$url}' style='max-height: 250px; border-radius: 8px; border: 1px solid #ddd; padding: 4px;' alt='Bukti' loading='lazy' />
                                </a>
                            </div>
                        ");
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(ReportExporter::class)
                    ->label('Export Laporan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->formats([
                        ExportFormat::Xlsx, 
                        ExportFormat::Csv,
                    ]),
            ])

            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('username')->label('Username')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('address')->searchable()->wrap()->limit(30),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'danger',
                        'proses' => 'warning',
                        'selesai' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })->badge()->alignCenter(),
                Tables\Columns\TextColumn::make('waktu_lapor')->sortable()->dateTime('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name'),
                Infolists\Components\TextEntry::make('username'),
                Infolists\Components\TextEntry::make('email'),
                Infolists\Components\TextEntry::make('address'),
                Infolists\Components\TextEntry::make('status'),
                Infolists\Components\ImageEntry::make('image')->width(400)->height('auto'),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
        ];
    }
}