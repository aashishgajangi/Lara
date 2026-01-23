<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getRawState();
        
        // Persist weight_unit to SEO metadata
        if (isset($data['weight_unit'])) {
            $seo = $record->seo()->firstOrCreate([]);
            $markup = $seo->schema_markup ?? [];
            $markup['weight_unit'] = $data['weight_unit'];
            $seo->schema_markup = $markup;
            $seo->save();
        }
    }
}
