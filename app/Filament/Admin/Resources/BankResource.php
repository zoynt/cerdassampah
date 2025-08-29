<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\Bank;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\BankResource\Pages;
use App\Filament\Admin\Resources\BankResource\RelationManagers;
use Filament\Forms\Components\TimePicker;

class BankResource extends Resource
{
    protected static ?string $model = Bank::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Lokasi Pengelola Sampah';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Bank Sampah';
    protected static ?string $pluralModelLabel = 'Bank Sampah'; // Nama di semua tempat




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
                        $set('bank_latitude', $state['lat']);
                        $set('bank_longitude', (string) $state['lng']);
                        return; // Hentikan proses jika alamat terkunci
                    }

                    // === CEK HIDRASI DATA ===
                    if (
                        (string) $state['lat'] === (string) $get('bank_latitude') &&
                        (string) $state['lng'] === (string) $get('bank_longitude')
                    ) {
                        return;
                    }

                    // === SET KOORDINAT ===
                    $latitude = $state['lat'];
                    $longitude = $state['lng'];

                    $set('bank_latitude', $latitude);
                    $set('bank_longitude', (string) $longitude);

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
                            $set('bank_address', 'Alamat tidak dapat ditemukan.');
                            $set('kecamatan', null);
                        } else {
                            $addressData = $data['address'] ?? [];
                            $set('bank_address', $data['display_name'] ?? 'Alamat tidak ditemukan');
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
                    if ($record?->bank_latitude && $record?->bank_longitude) {
                        $set('location', [
                            'lat' => $record->bank_latitude,
                            'lng' => $record->bank_longitude,
                        ]);
                    }
                }),

            // ===================== KOORDINAT =====================
            Forms\Components\TextInput::make('bank_latitude')
                ->label('Latitude')
                ->required(),

            Forms\Components\TextInput::make('bank_longitude')
                ->label('Longitude')
                ->required(),

            // ===================== ALAMAT =====================
            Forms\Components\Textarea::make('bank_address')
                ->label('Alamat Lengkap (Otomatis/Manual)')
                ->rows(3)
                ->helperText('Alamat akan terisi otomatis dari peta, namun Anda bisa mengoreksinya jika perlu.'),
            TextInput::make('kecamatan'),

            Forms\Components\Hidden::make('address_json'),
                TextInput::make('bank_name'),
                TextInput::make('bank_day'),
            TimePicker::make('bank_start_time')
                ->seconds(false),
            TimePicker::make('bank_end_time')
                ->seconds(false),
                Textarea::make('bank_description'),
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
                TextColumn::make('bank_start_time')->searchable()
                ->label('Buka')
                ->dateTime('H:i'),
                TextColumn::make('bank_end_time')->searchable()
                ->label('Tutup')
                ->dateTime('H:i'),
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
