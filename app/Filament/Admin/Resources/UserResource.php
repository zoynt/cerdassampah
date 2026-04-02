<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-c-user-group';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna'; // Nama di semua tempat
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('role')
                ->label('Role')
                ->options([
                    'warga' => 'Warga',
                    'banker' => 'Banker',
                    'seller' => 'Seller',
                ])
                ->required()
                ->default('seller')
                ->afterStateUpdated(function ($state, $record) {
                    $record->syncRoles([$state]); // update role di DB
                }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('username')->searchable()
                ->placeholder('No Username'),
                Tables\Columns\TextColumn::make('email')->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_telepon')
                ->label('No. Telepon')
                ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('role')
                // ->label('Role')
                // ->formatStateUsing(fn ($state) => ucfirst($state))
                // ->sortable()
                // ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                // ->since()
                ->dateTime('d M Y') // Format manual
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('setBanker')
                    ->label('Jadikan Banker')
                    ->icon('heroicon-o-check-badge')
                    ->action(function ($record) {
                        $record->syncRoles(['banker']);
                    })
                    ->requiresConfirmation()
                    ->color('success'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
