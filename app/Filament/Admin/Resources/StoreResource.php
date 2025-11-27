<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Pages\ViewStore;
use App\Models\Store;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\StoreResource\Pages;
use App\Filament\Admin\Resources\StoreResource\RelationManagers;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Lokasi Pengelola Sampah';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Marketplace';
    protected static ?string $pluralModelLabel = 'Marketplace';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Toggle::make('is_active')
                ->label('Status Toko')
                ->onColor('success')
                ->offColor('secondary')
                ->inline(false)
                ->columnSpanFull(),
            TextInput::make('name')
                ->label('Nama Toko')
                ->required()
                ->maxLength(255),
            Select::make('user_id')
                ->relationship('user', 'username', fn($query) => $query->whereNotNull('username'))
                ->label('Pengelola')
                ->searchable()
                ->preload(),
            TextInput::make('phone_number')
                ->label('Nomor Telepon')
                ->required()
                ->maxLength(20),
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
            
            TimePicker::make('opening_hour')
                ->seconds(false)
                ->label('Jam Buka')
                ->required(),
            TimePicker::make('closing_hour')
                ->seconds(false)
                ->label('Jam Tutup')
                ->required(),
            Map::make('location')
                ->label('Lokasi')
                ->zoom(15)
                ->columnSpanFull()
                ->defaultLocation(latitude: -3.291148218435594, longitude: 114.59807392192337),
            TextArea::make('address')
                ->label('Alamat Lengkap')
                ->required()
                ->columnSpanFull()
                ->maxLength(500),
            TextInput::make('district')
                ->label('Kecamatan')
                ->required()
                ->maxLength(255),
            TextInput::make('sub_district')
                ->label('Kelurahan')
                ->required()
                ->maxLength(255),
            Textarea::make('description'),
            Placeholder::make('image_preview')
                ->label('Gambar Toko')
                ->content(function ($record): ?HtmlString {
                    if ($record && $record->image_path) {
                        $url = Storage::disk('public')->url($record->image_path);
                        
                        return new HtmlString("<img src='{$url}' style='max-height: 200px;' alt='preview' />");
                    }
                    return null;
                })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->label('Nama Toko')->searchable()->sortable(),
                TextColumn::make('user.username')->label('Pengelola')->searchable(),
                // TextColumn::make('is_active')->label('Status Aktif')
                    // ->formatStateUsing(fn ($state) => $state == 1 ? 'Aktif' : 'Tidak Aktif'),
                TextColumn::make('is_active')->label('Status')
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })->formatStateUsing(fn ($state) => $state == 1 ? 'Aktif' : 'Tidak Aktif')
                    ->badge()
                    ->alignCenter(),
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
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListStores::route('/'),
            'view' => Pages\ViewStore::route('/{record}'),
            // 'create' => Pages\CreateStore::route('/create'),
            // 'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
