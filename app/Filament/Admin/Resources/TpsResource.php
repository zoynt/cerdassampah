<?php

namespace App\Filament\Admin\Resources;

use App\Models\Tps;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Ramsey\Uuid\Type\Time;
use PhpParser\Node\Stmt\Label;
use FIlament\Infolists\Infolist;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use function Laravel\Prompts\form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Filament\Exports\TpsExporter;

use App\Filament\Imports\TpsImporter;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Exports\ProductExporter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\ToggleButtons;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Infolists; // <-- Jangan lupa import
use App\Filament\Admin\Resources\TpsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Events\ModelPruningFinished;
use App\Filament\Admin\Resources\TpsResource\RelationManagers;


class TpsResource extends Resource
{
    protected static ?string $model = Tps::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'TPS';
    protected static ?string $navigationGroup = 'Lokasi Pengelola Sampah';

private const DAYS_OPTIONS = [
    'Senin' => 'Senin',
    'Selasa' => 'Selasa',
    'Rabu' => 'Rabu',
    'Kamis' => 'Kamis',
    'Jumat' => 'Jumat',
    'Sabtu' => 'Sabtu',
    'Minggu' => 'Minggu',
];
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            // ===================== TOGGLE KUNCI ALAMAT =====================
            Forms\Components\Toggle::make('lock_address')
                ->label('Kunci Alamat')
                ->default(true)
                ->helperText('Jika aktif, memindahkan peta hanya akan memperbarui koordinat, tidak dengan alamat.'),

            // ===================== MAP LOKASI =====================
            Map::make('location')
                ->label('Pilih Lokasi di Peta')
                ->zoom(15)
                ->columnSpanFull()
                ->defaultLocation(latitude: -3.291148218435594, longitude: 114.59807392192337)
                ->afterStateUpdated(function (Get $get, Set $set, ?array $state): void {
                    if (!isset($state['lat']) || !isset($state['lng'])) {
                        return;
                    }

                    // === LOGIKA KUNCI ALAMAT ===
                    if ($get('lock_address')) {
                        $set('tps_latitude', $state['lat']);
                        $set('tps_longitude', (string) $state['lng']);
                        return; // Hentikan proses jika alamat terkunci
                    }

                    // === CEK HIDRASI DATA ===
                    if (
                        (string) $state['lat'] === (string) $get('tps_latitude') &&
                        (string) $state['lng'] === (string) $get('tps_longitude')
                    ) {
                        return;
                    }

                    // === SET KOORDINAT ===
                    $latitude = $state['lat'];
                    $longitude = $state['lng'];

                    $set('tps_latitude', $latitude);
                    $set('tps_longitude', (string) $longitude);

                    // === PANGGIL API UNTUK AMBIL ALAMAT ===
                    try {
                        $response = Http::withHeaders([
                            'User-Agent' => config('app.name') . '/' . config('app.url'),
                        ])
                            ->withoutVerifying()
                            ->get("https://nominatim.openstreetmap.org/reverse", [
                                'lat'    => $latitude,
                                'lon'    => $longitude,
                                'format' => 'jsonv2',
                            ]);

                        $data = $response->json();

                        if ($response->failed() || isset($data['error'])) {
                            $set('tps_address', 'Alamat tidak dapat ditemukan.');
                            $set('kecamatan', null);
                        } else {
                            $addressData = $data['address'] ?? [];
                            $set('tps_address', $data['display_name'] ?? 'Alamat tidak ditemukan');
                            $set('kecamatan', $addressData['city_district']
                                ?? $addressData['suburb']
                                ?? $addressData['county']
                                ?? null);
                            $set('address_json', $data);
                        }
                    } catch (\Exception $e) {
                        $set('address', 'Gagal terhubung ke layanan peta.');
                        $set('kecamatan', null);
                        Log::error('Nominatim Connection Exception: ' . $e->getMessage());
                    }
                })
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    if ($record?->tps_latitude && $record?->tps_longitude) {
                        $set('location', [
                            'lat' => $record->tps_latitude,
                            'lng' => $record->tps_longitude,
                        ]);
                    }
                }),

            // ===================== KOORDINAT =====================
            Forms\Components\TextInput::make('tps_latitude')
                ->label('Latitude')
                ->required(),

            Forms\Components\TextInput::make('tps_longitude')
                ->label('Longitude')
                ->required(),

            // ===================== ALAMAT =====================
            Forms\Components\Textarea::make('tps_address')
                ->label('Alamat Lengkap (Otomatis/Manual)')
                ->rows(3)
                ->helperText('Alamat akan terisi otomatis dari peta, namun Anda bisa mengoreksinya jika perlu.'),

            Forms\Components\Hidden::make('address_json'),

            Forms\Components\Select::make('kecamatan')
                ->label('Kecamatan')
                ->options([
                    'banjarmasin utara'   => 'banjarmasin utara',
                    'banjarmasin selatan' => 'banjarmasin selatan',
                    'banjarmasin timur'   => 'banjarmasin timur',
                    'banjarmasin barat'   => 'banjarmasin barat',
                    'banjarmasin tengah'  => 'banjarmasin tengah',
                ])
                ->searchable()
                ->helperText('Dipilih otomatis dari peta, namun Anda bisa memilih dari daftar jika perlu.'),


            // ===================== INFORMASI TPS =====================
            Forms\Components\TextInput::make('tps_name')
                ->label('Nama TPS')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('tps_status')
                ->label('Status TPS')
                ->options([
                    'liar'   => 'liar',
                    'resmi' => 'resmi',
                ])
                ->searchable()
                ->required(),

            TimePicker::make('tps_start_time')
                ->label('Tanggal Mulai')
                ->seconds(false)
                ->required(),

            TimePicker::make('tps_end_time')
                ->label('Jam Selesai')
                ->seconds(false)
                ->required(),

            Forms\Components\CheckboxList::make('tps_day')
                ->label('Hari Operasional')
                ->options([
                    'Senin' => 'Senin',
                    'Selasa' => 'Selasa',
                    'Rabu' => 'Rabu',
                    'Kamis' => 'Kamis',
                    'Jumat' => 'Jumat',
                    'Sabtu' => 'Sabtu',
                    'Minggu' => 'Minggu',
                ])
                ->required()
                ->columns(3)
                ->gridDirection('row')
                ->bulkToggleable(), // <-- Tambahkan baris ini 👍

                Forms\Components\TextInput::make('tps_transport')
                ->label('Transportasi')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('tps_description')
                ->label('Deskripsi TPS')
                ->required()
                ->maxLength(300),

            // ===================== UPLOAD GAMBAR =====================
            FileUpload::make('image')
                ->image()
                // ->imageEditor()
                ->label('Gambar TPS'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(TpsExporter::class)
                    ->label('Export TPS')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->formats([
                        ExportFormat::Xlsx,
                    ]),
                Tables\Actions\ImportAction::make()
                    ->importer(TpsImporter::class)
                    ->label('Import TPS')
                    ->icon('heroicon-o-arrow-up-tray')
            ])

            ->columns([
                Tables\Columns\TextColumn::make('tps_name')->searchable()
                ->label('Nama TPS'),
                Tables\Columns\TextColumn::make('kecamatan')->searchable()
                ->label('Kecamatan'),
                Tables\Columns\TextColumn::make('tps_status')->searchable()
                ->color(fn (string $state): string => match ($state) {
                    'resmi' => 'success',
                    'liar' => 'danger',
                })
                ->badge()
                ->alignCenter()
                ->label('Status TPS'),
                
                Tables\Columns\TextColumn::make('tps_day')->searchable()
                ->label('Hari Operasional')
                ->wrap()
                ->searchable(),

                Tables\Columns\TextColumn::make('tps_start_time')->searchable()
                ->dateTime('H:i')
                ->label('Jam Buka'),
                Tables\Columns\TextColumn::make('tps_end_time')->searchable()
                ->dateTime('H:i')
                ->label('Jam Tutup'),
                Tables\Columns\TextColumn::make('tps_transport')->searchable()
                ->label('Transportasi'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kecamatan')
                    ->label('Kecamatan')
                    ->options(
                        // Ambil semua nilai unik dari kolom 'tps_kecamatan' dan jadikan pilihan
                        Tps::query()->distinct()->pluck('kecamatan', 'kecamatan')->all()
                    ),
                Tables\Filters\SelectFilter::make('tps_day')
                ->label('Hari Operasional')
                ->options(self::DAYS_OPTIONS)
                ->query(function (Builder $query, array $data): Builder {
                    $value = $data['value'];

                    // Jika tidak ada hari yang dipilih, jangan filter apa-apa
                    if (blank($value)) {
                        return $query;
                    }
                    
                    // <-- Kunci utamanya di sini
                    // Cari record di mana kolom 'tps_day' mengandung nilai yang dipilih
                    return $query->whereJsonContains('tps_day', $value);
                })
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
            // 'edit' => Pages\EditTps::route('/{record}/edit'),
        ];
    }
}
