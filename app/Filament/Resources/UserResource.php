<?php
namespace App\Filament\Resources;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                            ->required() // cannot empty
                                            ->maxLength(255), // max char 255
                Forms\Components\TextInput::make('email')
                                            ->required() // cannot empty
                                            ->email() // email validation
                                            ->maxLength(255), // max char 255
                Forms\Components\TextInput::make('password')
                                            ->required() // cannot empty
                                            ->password() //  password text input
                                            ->revealable() // hide show password
                                            ->maxLength(255), // max char 255
                                            // Add role selection
                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name') // Assuming User has a many-to-many relation with Role
                    ->required()
                    ->multiple(true) // Allow multiple roles
                    ->preload(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                       Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                 // Display roles
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Roles')
                    ->wrap()
                    ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}