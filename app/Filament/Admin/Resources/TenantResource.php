<?php

namespace App\Filament\Admin\Resources;

use App\Models\Tenant;
use Filament\{Tables};
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Pages\ManageTenantUsers;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Jozenetoz\FilamentPtbrFormFields\{Cep, Cnpj, Document};
use Filament\Forms\Components\{Section, TextInput};
use App\Filament\Admin\Resources\TenantResource\{Pages};
use App\Filament\Admin\Resources\TenantResource\RelationManagers\DomainsRelationManager;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Empresas';

    protected static ?string $pluralLabel = 'Empresas';

    protected static ?string $label = 'Empresa';

    protected static ?string $slug = 'empresas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Informações Básicas')
                    ->columns(2)
                    ->schema([
                        Cnpj::make('cnpj')
                            ->label('Documento')
                            ->brasilApi(
                                mode: 'suffix',
                                errorMessage: 'CNPJ inválido.',
                                setFields: [
                                    'name'         => 'razao_social',
                                    'postal_code'  => 'cep',
                                    'city'         => 'municipio',
                                    'state'        => 'uf',
                                    'neighborhood' => 'bairro',
                                    'street'       => 'logradouro',
                                    'number'       => 'numero',
                                    'complement'   => 'complemento',
                                    'fantasy_name' => 'nome_fantasia',
                                ],
                            )
                            ->required(),
                        TextInput::make('email')
                            ->label('Email')
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Razão Social')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('fantasy_name')
                            ->label('Nome Fantasia')
                            ->maxLength(255),


                    ]),
                Section::make('Endereço')
                    ->columns(3)
                    ->schema([

                        Cep::make('postal_code')
                        ->label('CEP')
                        ->helperText('Digite o CEP e clique no botão para preencher as informações de endereço.')
                        ->brasilApi(
                            mode: 'suffix',
                            errorMessage: 'CEP inválido.',
                            setFields: [
                                'city'         => 'city',
                                'state'        => 'state',
                                'neighborhood' => 'neighborhood',
                                'street'       => 'street',
                                'number'       => 'number',
                                'complement'   => 'complement',
                            ]
                        )
                        ->afterStateUpdated(function ($state, callable $set) {
                            // Formata o CEP no formato 99999-99
                            if ($state) {
                                $formattedCep = preg_replace('/(\d{5})(\d{3})/', '$1-$2', $state);
                                $set('cep', $formattedCep);
                            }
                        })
                        ->required()
                        ->maxLength(10),

                        TextInput::make('city')
                            ->label('Município')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('state')
                            ->label('Estado')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('neighborhood')
                            ->label('Bairro')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('street')
                            ->label('Rua')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('number')
                            ->label('Número')
                            ->maxLength(255)
                            ->required(),

                        TextInput::make('complement')
                            ->label('Complemento')
                            ->columnSpanFull()
                            ->maxLength(255),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Nome'),
                TextColumn::make('email')
                    ->label('Email'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditTenant::class,
            Pages\ManageTenantSubdomains::class,
            Pages\ManageTenantUsers::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/criar'),
            'edit'   => Pages\EditTenant::route('/{record}/editar'),
            'subdomains' => Pages\ManageTenantSubdomains::route('/{record}/subdominios'),
            'users' => Pages\ManageTenantUsers::route('/{record}/usuarios'),
        ];
    }
}
