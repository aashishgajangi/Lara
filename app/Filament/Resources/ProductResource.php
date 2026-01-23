<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Catalog';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Product Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\TextInput::make('slug')->required(),
                                    ]),
                                Forms\Components\Textarea::make('short_description')
                                    ->rows(2)
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('description')
                                    ->columnSpanFull()
                                    ->toolbarButtons([
                                        'bold', 'italic', 'underline', 'bulletList', 'orderedList', 'link'
                                    ]),
                            ])->columns(2),
                            
                        Forms\Components\Tabs\Tab::make('Pricing & Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('₹')
                                    ->minValue(0)
                                    ->step(0.01),
                                Forms\Components\TextInput::make('discount_price')
                                    ->label('Sale Price')
                                    ->numeric()
                                    ->prefix('₹')
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->helperText('Leave empty if no sale'),
                                Forms\Components\TextInput::make('stock_quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                Forms\Components\TextInput::make('low_stock_threshold')
                                    ->required()
                                    ->numeric()
                                    ->default(5)
                                    ->helperText('Alert when stock falls below this number'),
                            ])->columns(2),
                            
                            // Images tab moved to Relation Manager
                            
                        Forms\Components\Tabs\Tab::make('Settings')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->default(true)
                                    ->helperText('Inactive products are hidden from customers'),
                                Forms\Components\Toggle::make('is_featured')
                                    ->default(false)
                                    ->helperText('Featured products appear on homepage'),
                            ])->columns(2),
                            
                        Forms\Components\Tabs\Tab::make('Shipping & SEO')
                            ->schema([
                                Forms\Components\Section::make('Physical Attributes')
                                    ->description('Product dimensions and weight for shipping')
                                    ->schema([
                                        Forms\Components\TextInput::make('weight')
                                            ->numeric()
                                            ->step(0.01)
                                            ->helperText('Weight value'),
                                        Forms\Components\Select::make('weight_unit')
                                            ->label('Weight Unit')
                                            ->options([
                                                'KGM' => 'Kilograms (KGM)',
                                                'GRM' => 'Grams (GRM)',
                                                'LBR' => 'Pounds (LBR)',
                                                'ONZ' => 'Ounces (ONZ)',
                                            ])
                                            ->default('KGM')
                                            ->afterStateHydrated(fn ($component, $record) => $component->state($record?->seo?->schema_markup['weight_unit'] ?? 'KGM'))
                                            ->dehydrated(false), // Handled manually in Create/Edit pages
                                        Forms\Components\TextInput::make('dimensions')
                                            ->maxLength(255)
                                            ->placeholder('L x W x H')
                                            ->columnSpanFull(),
                                    ])->columns(2),

                                Forms\Components\Section::make('Search Engine Optimization')
                                    ->description('Customize how this product appears in search results')
                                    ->relationship('seo')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60)
                                            ->placeholder(fn (Forms\Get $get) => $get('../../name')) // Use parent form state if possible, otherwise plain placeholder
                                            ->helperText('Leave empty to use product name'),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->rows(2)
                                            ->helperText('Leave empty to use short description'),
                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label('Keywords')
                                            ->placeholder('keyword1, keyword2'),
                                        
                                        Forms\Components\Section::make('Schema.org Data')
                                            ->description('Rich snippet data for search engines')
                                            ->schema([
                                                Forms\Components\TextInput::make('schema_markup.brand')
                                                    ->label('Brand Name')
                                                    ->placeholder('e.g. Nike, Apple'),
                                                Forms\Components\TextInput::make('schema_markup.manufacturer')
                                                    ->label('Manufacturer'),
                                                Forms\Components\TextInput::make('schema_markup.gtin')
                                                    ->label('GTIN / Barcode')
                                                    ->helperText('Global Trade Item Number (EAN, UPC, ISBN)'),
                                                Forms\Components\TextInput::make('schema_markup.mpn')
                                                    ->label('MPN')
                                                    ->helperText('Manufacturer Part Number'),
                                                Forms\Components\Select::make('schema_markup.condition')
                                                    ->label('Item Condition')
                                                    ->options([
                                                        'NewCondition' => 'New',
                                                        'UsedCondition' => 'Used',
                                                        'RefurbishedCondition' => 'Refurbished',
                                                        'DamagedCondition' => 'Damaged',
                                                    ])
                                                    ->default('NewCondition'),
                                                Forms\Components\TextInput::make('schema_markup.color')
                                                    ->label('Color'),
                                                Forms\Components\TextInput::make('schema_markup.material')
                                                    ->label('Material'),
                                            ])->columns(2),
                                    ]),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_price')
                    ->label('Sale Price')
                    ->money('INR')
                    ->sortable()
                    ->placeholder('—')
                    ->color('success'),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state, $record) => $record->isLowStock() ? 'danger' : 'success'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->label('Featured')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All products')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All products')
                    ->trueLabel('Featured only')
                    ->falseLabel('Not featured'),
                Tables\Filters\Filter::make('low_stock')
                    ->query(fn (Builder $query): Builder => $query->whereRaw('stock_quantity <= low_stock_threshold'))
                    ->label('Low Stock'),
                Tables\Filters\Filter::make('out_of_stock')
                    ->query(fn (Builder $query): Builder => $query->where('stock_quantity', 0))
                    ->label('Out of Stock'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
