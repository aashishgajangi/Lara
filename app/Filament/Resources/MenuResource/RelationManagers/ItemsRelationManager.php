<?php

namespace App\Filament\Resources\MenuResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'allItems';

    protected static ?string $title = 'Menu Items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Parent Item')
                            ->relationship('parent', 'title', function (Builder $query) {
                                $query->where('menu_id', $this->getOwnerRecord()->id)
                                    ->whereNull('parent_id');
                            })
                            ->placeholder('None (Top Level)')
                            ->searchable()
                            ->helperText('Select a parent to create a submenu item'),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Home, About Us, Contact'),
                        
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->placeholder('e.g., /about, https://example.com')
                            ->helperText('Leave empty for items with submenus')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('target')
                            ->options([
                                '_self' => 'Same Window',
                                '_blank' => 'New Window',
                            ])
                            ->default('_self')
                            ->required(),
                        
                        Forms\Components\TextInput::make('icon')
                            ->placeholder('e.g., heroicon-o-home')
                            ->helperText('Optional icon class')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('css_class')
                            ->label('CSS Class')
                            ->placeholder('e.g., featured-link')
                            ->helperText('Optional custom CSS classes')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers appear first'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->parent ? '↳ Submenu of: ' . $record->parent->title : null),
                
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->placeholder('—'),
                
                Tables\Columns\BadgeColumn::make('target')
                    ->formatStateUsing(fn (string $state): string => $state === '_blank' ? 'New Window' : 'Same Window')
                    ->colors([
                        'primary' => '_self',
                        'warning' => '_blank',
                    ]),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Subitems')
                    ->counts('children')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All items')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                Tables\Filters\Filter::make('top_level')
                    ->label('Top Level Only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('parent_id')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['menu_id'] = $this->getOwnerRecord()->id;
                        return $data;
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }
}
