<?php

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Filament\Admin\Resources\TenantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenant extends EditRecord
{
    protected static string $resource = TenantResource::class;

    public function getTitle(): string
    {
        return 'Editar Empresa';
    }

    public function getBreadcrumb(): string
    {
        return 'Editar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Editar';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
