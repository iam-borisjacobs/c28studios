<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PricingTierResource\Pages;
use App\Models\PricingTier;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PricingTierResource extends Resource
{
    protected static ?string $model = PricingTier::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Studio Architecture';

    protected static ?string $navigationLabel = 'Pricing & Engagements';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->active()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Engagement Tier & Billing Configuration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Package Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create' && $state ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('tier_badge')
                            ->label('Tier Badge')
                            ->placeholder('02 / CORE SPECIALIZATION')
                            ->maxLength(100),

                        Select::make('billing_mode')
                            ->label('Billing Mode')
                            ->options(PricingTier::BILLING_MODES)
                            ->required(),

                        TextInput::make('price_amount')
                            ->label('Numeric Price Amount (USD)')
                            ->numeric()
                            ->minValue(0),

                        TextInput::make('price_label')
                            ->label('Formatted Price Label')
                            ->placeholder('$2,500 or $4,200+ or Custom')
                            ->required()
                            ->maxLength(64),

                        TextInput::make('billing_period')
                            ->label('Billing Period Suffix')
                            ->placeholder('/ typical sprint')
                            ->maxLength(100),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_highlighted')
                            ->label('Highlighted (Most Popular Glow)')
                            ->default(false),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),

                Section::make('Audience, Deliverables & Call to Action')
                    ->columns(2)
                    ->schema([
                        Textarea::make('target_audience')
                            ->label('Target Audience Summary')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('features_heading')
                            ->label('Features List Heading')
                            ->placeholder("What's Included:")
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TagsInput::make('features')
                            ->label('Included Features (Press Enter after each feature)')
                            ->placeholder('Add feature...')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('cta_label')
                            ->label('CTA Button Label')
                            ->default('Book Consultation')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('cta_url')
                            ->label('CTA Target URL or Anchor')
                            ->default('#consultation')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('tier_badge')
                    ->label('Badge')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('billing_mode')
                    ->label('Mode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => PricingTier::BILLING_MODES[$state] ?? $state)
                    ->color(fn (string $state): string => $state === PricingTier::MODE_SPRINT_FIXED ? 'success' : 'info'),

                TextColumn::make('price_label')
                    ->label('Price')
                    ->fontFamily('mono')
                    ->sortable('price_amount'),

                TextColumn::make('billing_period')
                    ->label('Period'),

                ToggleColumn::make('is_highlighted')
                    ->label('Popular'),

                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('billing_mode')
                    ->options(PricingTier::BILLING_MODES),
                TernaryFilter::make('is_active'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricingTiers::route('/'),
            'create' => Pages\CreatePricingTier::route('/create'),
            'edit' => Pages\EditPricingTier::route('/{record}/edit'),
        ];
    }
}

