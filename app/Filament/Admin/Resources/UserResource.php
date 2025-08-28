<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-c-user-group';
<<<<<<< HEAD
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $pluralModelLabel = 'Pengguna'; // Nama di semua tempat
    protected static ?int $navigationSort = 0;
=======
>>>>>>> 011cfcc (Add filament for Admin's Panel)

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
<<<<<<< HEAD
                Tables\Columns\TextColumn::make('username')->searchable()
                ->placeholder('No Username'),
                Tables\Columns\TextColumn::make('email')->searchable()
                ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan default
                Tables\Columns\TextColumn::make('created_at')
                // ->since()
=======
                Tables\Columns\TextColumn::make('username')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()
                ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan default
                Tables\Columns\TextColumn::make('created_at')
>>>>>>> 011cfcc (Add filament for Admin's Panel)
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan default
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
<<<<<<< HEAD
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
=======
            'edit' => Pages\EditUser::route('/{record}/edit'),
>>>>>>> 011cfcc (Add filament for Admin's Panel)
        ];
    }
}
