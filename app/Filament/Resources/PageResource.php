<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        // LEFT COLUMN - Main Content
                        Forms\Components\Group::make()
                            ->schema([
                                // Basic Info Section
                                Forms\Components\Section::make('Basic Information')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label('Page Title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($operation, $state, Forms\Set $set) => 
                                                $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null
                                            ),
                                        
                                        Forms\Components\TextInput::make('slug')
                                            ->label('URL Slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true)
                                            ->helperText('Auto-generated from title'),
                                    ])->columns(2),

                                // CONTENT BLOCKS
                                Forms\Components\Section::make('Page Content')
                                    ->icon('heroicon-o-squares-plus')
                                    ->schema([
                                        Forms\Components\Builder::make('blocks')
                                            ->label('Content Blocks')
                                            ->blocks([
                                                // HERO BLOCK
                                                Forms\Components\Builder\Block::make('hero')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('title')
                                                            ->label('Headline')
                                                            ->required(),
                                                        Forms\Components\Textarea::make('subtitle')
                                                            ->label('Subtitle')
                                                            ->rows(2),
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Background Image')
                                                            ->image()
                                                            ->directory('blocks/hero')
                                                            ->preserveFilenames(),
                                                        Forms\Components\Grid::make(2)->schema([
                                                            Forms\Components\TextInput::make('button_text')
                                                                ->label('Button Text'),
                                                            Forms\Components\TextInput::make('button_url')
                                                                ->label('Button URL'),
                                                        ]),
                                                    ])->icon('heroicon-o-star'),

                                                // TEXT BLOCK
                                                Forms\Components\Builder\Block::make('text_content')
                                                    ->label('Rich Text')
                                                    ->schema([
                                                        Forms\Components\RichEditor::make('content')
                                                            ->label('Content')
                                                            ->required(),
                                                        Forms\Components\Select::make('content_width')
                                                            ->label('Content Width')
                                                            ->options([
                                                                'narrow' => 'Narrow (For Reading)',
                                                                'full' => 'Full Width',
                                                            ])
                                                            ->default('narrow'),
                                                    ])->icon('heroicon-o-document-text'),

                                                // IMAGE BLOCK
                                                Forms\Components\Builder\Block::make('image_block')
                                                    ->label('Image')
                                                    ->schema([
                                                        Forms\Components\FileUpload::make('image')
                                                            ->label('Image')
                                                            ->image()
                                                            ->directory('blocks/content')
                                                            ->required(),
                                                        Forms\Components\Select::make('layout')
                                                            ->options([
                                                                'full' => 'Full Width',
                                                                'contained' => 'Contained',
                                                            ])
                                                            ->default('contained'),
                                                        Forms\Components\TextInput::make('caption')
                                                            ->label('Caption'),
                                                    ])->icon('heroicon-o-photo'),

                                                // STATS BLOCK
                                                Forms\Components\Builder\Block::make('stats')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('items')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('value')->label('Value (e.g. 100+)'),
                                                                Forms\Components\TextInput::make('label')->label('Label'),
                                                            ])->grid(3),
                                                    ])->icon('heroicon-o-chart-bar'),

                                                // TEAM BLOCK
                                                Forms\Components\Builder\Block::make('team')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('heading')->default('Our Team'),
                                                        Forms\Components\Repeater::make('members')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('name')->required(),
                                                                Forms\Components\TextInput::make('role')->required(),
                                                                Forms\Components\FileUpload::make('image')
                                                                    ->label('Photo')
                                                                    ->image()
                                                                    ->directory('blocks/team'),
                                                                Forms\Components\Textarea::make('bio')->rows(3),
                                                            ])->grid(2)->collapsible(),
                                                    ])->icon('heroicon-o-users'),

                                                // CONTACT BLOCK
                                                Forms\Components\Builder\Block::make('contact')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('heading')->default('Contact Us'),
                                                        Forms\Components\Toggle::make('show_form')->default(true)->label('Show Contact Form'),
                                                        Forms\Components\Textarea::make('address')->rows(2),
                                                        Forms\Components\TextInput::make('phone'),
                                                        Forms\Components\TextInput::make('email'),
                                                    ])->icon('heroicon-o-envelope'),

                                                // PRODUCT GRID
                                                Forms\Components\Builder\Block::make('product_grid')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('heading')->default('Featured Products'),
                                                        Forms\Components\TextInput::make('count')->numeric()->default(8),
                                                    ])->icon('heroicon-o-shopping-bag'),

                                                // NEWSLETTER
                                                Forms\Components\Builder\Block::make('newsletter')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('heading')->default('Subscribe'),
                                                        Forms\Components\Textarea::make('text')->default('Stay updated with our latest news.'),
                                                    ])->icon('heroicon-o-paper-airplane'),
                                            ])
                                            ->collapsible()
                                            ->collapsed(),
                                    ]),

                                // SEO SECTION
                                Forms\Components\Section::make('SEO Settings')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->collapsed()
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->rows(3)
                                            ->maxLength(160),
                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label('Meta Keywords'),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // RIGHT COLUMN - Settings Sidebar
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Page Settings')
                                    ->icon('heroicon-o-cog')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true),
                                        
                                        Forms\Components\Toggle::make('is_homepage')
                                            ->label('Set as Homepage')
                                            ->helperText('Only one page can be homepage'),
                                        
                                        Forms\Components\Toggle::make('show_title')
                                            ->label('Show Page Title')
                                            ->default(true),
                                        
                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0),
                                    ]),

                                Forms\Components\Section::make('Quick Actions')
                                    ->schema([
                                        Forms\Components\Placeholder::make('created_at')
                                            ->label('Created')
                                            ->content(fn ($record) => $record?->created_at?->diffForHumans() ?? '-'),
                                        Forms\Components\Placeholder::make('updated_at')
                                            ->label('Last Updated')
                                            ->content(fn ($record) => $record?->updated_at?->diffForHumans() ?? '-'),
                                    ])
                                    ->hidden(fn ($operation) => $operation === 'create'),
                            ])
                            ->columnSpan(1),
                    ]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label('Homepage')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Who Updated')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
