<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Filament\Admin\Resources\TenantResource;
use App\Models\Tenant;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageTenantUsers extends ManageRelatedRecords
{
    protected static string $resource = TenantResource::class;

    protected static string $relationship = 'users';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public function getTitle(): string
    {
        return 'Usuários';
    }

    public function getBreadcrumb(): string
    {
        return 'Usuários';
    }

    public static function getNavigationLabel(): string
    {
        return 'Usuários';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->confirmed()
                    ->revealable()
                    ->autocomplete(false)
                    ->minLength(8)
                    ->maxLength(50),


                TextInput::make('password_confirmation')
                    ->label('Confirmar Senha')
                    ->password()
                    ->revealable()
                    ->requiredWith('password')
                    ->autocomplete(false)
                    ->minLength(8)
                    ->maxLength(50),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $tenant = Tenant::find($this->record->id);
                tenancy()->initialize($tenant);

                return User::query();
            })
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Criar Usuário')
                    ->modalHeading('Criar Usuário')
                    ->using(function (array $data) {
                        $tenant = Tenant::find($this->record->id);
                        tenancy()->initialize($tenant);
                        $user = User::create($data);

                        tenancy()->end();
                        return $user;
                    }),
            ])
            ->actions([
                ViewAction::make()
                    ->label('Visualizar'),
                EditAction::make()
                    ->label('Editar'),
                DeleteAction::make()
                    ->label('Excluir'),

            ])
            ->bulkActions([
                //
            ]);
    }
}
