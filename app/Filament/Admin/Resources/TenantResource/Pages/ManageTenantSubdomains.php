<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Filament\Admin\Resources\TenantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageTenantSubdomains extends ManageRelatedRecords
{
    protected static string $resource = TenantResource::class;

    protected static string $relationship = 'domains';

    protected static ?string $navigationIcon = 'tabler-world-www';

    public function getTitle(): string
    {
        return 'Subdomínios';
    }

    public function getBreadcrumb(): string
    {
        return 'Subdomínios';
    }

    public static function getNavigationLabel(): string
    {
        return 'Subdomínios';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('domain')
                    ->label('Subdomínio')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
                    ->prefix(config('app.scheme') . '://')
                    ->suffix('.' . config('app.host'))
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading('Nenhum Subdomínio encontrado')
            ->emptyStateDescription('Crie um Subdomínio para poder acessar o sistema.')
            ->recordTitleAttribute('domain')
            ->columns([
                Tables\Columns\TextColumn::make('domain')
                    ->label('Subdomínio')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('full_link')
                    ->label('Link Completo')
                    ->disabled()
                    ->columnSpanFull()
                    ->copyable()
                    ->default(
                        function ($record) {
                            $scheme = config('app.scheme');
                            $host = config('app.host');
                            return $scheme . '://' . $record->domain . '.' . $host;
                        }
                    ),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Criar Subdomínio')
                    ->modalHeading('Criar Subdomínio'),
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
}
