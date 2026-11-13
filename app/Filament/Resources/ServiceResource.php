<?php

namespace App\Filament\Resources;

use App\Filament\Blocks\C28BuilderBlocks;
use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
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

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    protected static ?string $navigationGroup = 'Studio Architecture';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->active()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Service Identity & Hierarchy')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, ?string $state, Set $set) => $operation === 'create' && $state ? $set('slug', Str::slug($state)) : null),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Select::make('tier')
                            ->label('Service Tier (AGENTS.md Hierarchy)')
                            ->options(Service::TIERS)
                            ->required(),

                        TextInput::make('tier_badge')
                            ->label('Tier Badge Label')
                            ->placeholder('01 • Primary Service')
                            ->maxLength(64),

                        TextInput::make('icon')
                            ->label('Icon Identifier')
                            ->placeholder('server')
                            ->maxLength(100),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_featured')
                            ->label('Featured on Homepage')
                            ->default(true),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),

                Section::make('Outcome & Deliverables')
                    ->schema([
                        Textarea::make('short_outcome')
                            ->label('Customer-Focused Short Outcome')
                            ->rows(2)
                            ->required()
                            ->maxLength(500),

                        Textarea::make('description')
                            ->label('Full Engineering Description')
                            ->rows(4),

                        TagsInput::make('deliverables')
                            ->label('Key Deliverables (Press Enter after each deliverable)')
                            ->placeholder('Add deliverable item...'),
                    ]),

                Section::make('Dynamic Builder Blocks')
                    ->schema([
                        C28BuilderBlocks::make('builder_blocks', 'Service Section Blocks'),
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

                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tier')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Service::TIERS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Service::TIER_PRIMARY => 'success',
                        Service::TIER_SECONDARY => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('projects_count')
                    ->counts('projects')
                    ->label('Projects'),

                ToggleColumn::make('is_featured')
                    ->label('Featured'),

                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('tier')
                    ->options(Service::TIERS),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}

