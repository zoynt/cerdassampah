<?php

namespace App\Filament\Admin\Resources;

use Dom\Text;
use Filament\Forms;
use App\Models\Bank;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Filament\Exports\BankExporter;
use App\Filament\Imports\BankImporter;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BankResource\Pages;
use App\Filament\Admin\Resources\BankResource\RelationManagers;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Lokasi Pengelola Sampah';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Bank Sampah';
    protected static ?string $pluralModelLabel = 'Bank Sampah';




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
                        $set('latitude', $state['lat']);
                        $set('longitude', (string) $state['lng']);
                        return; // Hentikan proses jika alamat terkunci
                    }

                    // === CEK HIDRASI DATA ===
                    if (
                        (string) $state['lat'] === (string) $get('latitude') &&
                        (string) $state['lng'] === (string) $get('longitude')
                    ) {
                        return;
                    }

                    // === SET KOORDINAT ===
                    $latitude = $state['lat'];
                    $longitude = $state['lng'];

                    $set('latitude', $latitude);
                    $set('longitude', (string) $longitude);

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
                            $set('address', 'Alamat tidak dapat ditemukan.');
                            $set('district', null);
                        } else {
                            $addressData = $data['address'] ?? [];
                            $set('address', $data['display_name'] ?? 'Alamat tidak ditemukan');
                            // $set('district', $addressData['city_district']
                            //     ?? $addressData['suburb']
                            //     ?? $addressData['county']
                            //     ?? null);
                            $set('address_json', $data);
                        }
                    } catch (\Exception $e) {
                        $set('address', 'Gagal terhubung ke layanan peta.');
                        $set('district', null);
                        Log::error('Nominatim Connection Exception: ' . $e->getMessage());
                    }
                })
                ->afterStateHydrated(function ($state, $record, Set $set): void {
                    if ($record?->latitude && $record?->longitude) {
                        $set('location', [
                            'lat' => $record->latitude,
                            'lng' => $record->longitude,
                        ]);
                    }
                }),

            // ===================== KOORDINAT =====================
            Forms\Components\TextInput::make('latitude')
                ->label('Latitude')
                ->required(),

            Forms\Components\TextInput::make('longitude')
                ->label('Longitude')
                ->required(),

            // ===================== ALAMAT =====================
            Forms\Components\Textarea::make('address')
                ->label('Alamat Lengkap (Otomatis/Manual)')
                ->rows(3)
                ->helperText('Alamat akan terisi otomatis dari peta, namun Anda bisa mengoreksinya jika perlu.')
                ->columnSpanFull()
                ->required(),

            Forms\Components\Hidden::make('address_json'),
                Forms\Components\Select::make('district')
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

            Forms\Components\TextInput::make('sub_district')
                ->label('Kelurahan')
                ->required(),

            TextInput::make('bank_name')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true) 
                ->afterStateUpdated(function (Set $set, ?string $state) {
                    $set('slug', Str::slug($state));
                }),

            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->readOnly() 
                ->unique(ignoreRecord: true), 

            Forms\Components\Select::make('user_id')
                ->label('Pemilik (User)')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required()
                // ->visible(fn () => auth()->user()->hasRole('admin'))
                ->native(false)
                ->extraAttributes(['class' => 'relative z-[9999]']),
                // ->disabled(fn () => !auth()->user()->hasRole('admin')),            
            TimePicker::make('opening_hour')
                ->seconds(false)
                ->label('Jam Buka')
                ->required(),
            TimePicker::make('closing_hour')
                ->seconds(false)
                ->label('Jam Tutup')
                ->required(),
            FileUpload::make('image_path')
                ->image()
                ->imageEditor()
                ->Label('Gambar Bank'),
            Forms\Components\CheckboxList::make('operational_days')
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
                ->bulkToggleable(),
            Textarea::make('description')
                ->columnSpanFull()
                ->label('Deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(BankExporter::class)
                    ->label('Export Bank')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->formats([
                        ExportFormat::Xlsx,
                    ]),
                ImportAction::make()
                    ->importer(BankImporter::class)
                    ->label('Import Bank')
                    ->icon('heroicon-o-arrow-up-tray')
            ])

            ->columns([
                TextColumn::make('bank_name')->searchable()
                ->wrap(),
                TextColumn::make('user.username')->label('Pengelola')->searchable(),
                TextColumn::make('phone_number')->searchable()
                ->label('No. Telp'),
                TextColumn::make('district')->searchable()
                ->label('Kecamatan'),
                TextColumn::make('sub_district')->searchable()
                ->label('Kelurahan'),
                TextColumn::make('operational_days')->searchable()
                ->label('Hari Operasional')
                ->wrap(),
                TextColumn::make('opening_hour')->searchable()
                ->label('Buka')
                ->dateTime('H:i'),
                TextColumn::make('closing_hour')->searchable()
                ->label('Tutup')
                ->dateTime('H:i'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('district')
                    ->label('kecamatan')
                    ->options(
                        // Ambil semua nilai unik dari kolom 'bank_district' dan jadikan pilihan
                        Bank::query()->distinct()->pluck('district', 'district')->all()
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
