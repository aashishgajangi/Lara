<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Awcodes\Curator\Components\Forms\CuratorPicker::make('media_id')
                    ->label('Image')
                    ->size('sm')
                    ->constrained(true)
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('alt_text')
                    ->label('Alt Text (SEO)')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->label('Sort Order'),
                Forms\Components\Toggle::make('is_primary')
                    ->label('Primary Image')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('image_path')
            ->columns([
                Tables\Columns\ImageColumn::make('image_preview')
                    ->label('Image')
                    ->state(fn ($record) => $record->media ? $record->media->path : $record->image_path)
                    ->disk(fn ($record) => $record->media ? $record->media->disk : 'public')
                    ->size(80),
                Tables\Columns\TextColumn::make('alt_text')
                    ->label('Alt Text')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('sort_order')
                    ->sortable()
                    ->rules(['numeric', 'min:0']),
                Tables\Columns\IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray')
                    ->action(function ($record, \App\Filament\Resources\ProductResource\RelationManagers\ImagesRelationManager $livewire) {
                        $newState = !$record->is_primary;
                        
                        if ($newState) {
                            // Setting to Primary: Unset all others first
                            \App\Models\ProductImage::where('product_id', $record->product_id)
                                ->where('id', '!=', $record->id)
                                ->update(['is_primary' => false]);
                                
                            $record->update(['is_primary' => true]);
                        } else {
                            // Unsetting Primary
                            $record->update(['is_primary' => false]);
                        }
                        
                        // Force refresh of the table to update other rows
                        $livewire->dispatch('refresh-values'); 
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\Action::make('add_images')
                    ->label('Add Images')
                    ->icon('heroicon-o-photo')
                    ->form([
                        \Awcodes\Curator\Components\Forms\CuratorPicker::make('media_ids')
                            ->label('Select Images')
                            ->multiple()
                            ->required(),
                    ])
                    ->action(function (array $data, \App\Filament\Resources\ProductResource\RelationManagers\ImagesRelationManager $livewire): void {
                        $productId = $livewire->ownerRecord->id;
                        $mediaIds = $data['media_ids'];
                        
                        foreach ($mediaIds as $mediaId) {
                            // Convert mediaId to integer if it's an array (sometimes Curator returns objects/arrays depending on config)
                            $id = is_array($mediaId) ? $mediaId['id'] : $mediaId;
                            
                            \App\Models\ProductImage::create([
                                'product_id' => $productId,
                                'media_id' => $id,
                                'sort_order' => \App\Models\ProductImage::where('product_id', $productId)->max('sort_order') + 1,
                            ]);
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc');
    }
}
