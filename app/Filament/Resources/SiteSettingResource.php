<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Site Configuration';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Setting Key & Value')
                    ->columns(2)
                    ->schema([
                        TextInput::make('key')
                            ->label('Setting Key (e.g. hero.headline)')
                            ->required()
                            ->maxLength(120)
                            ->unique(ignoreRecord: true)
                            ->extraInputAttributes(['class' => 'font-mono text-xs']),

                        Select::make('group')
                            ->options([
                                'brand' => 'Brand Identity',
                                'hero' => 'Hero Section',
                                'console' => 'Interactive Console',
                                'blueprint' => '10-Step Blueprint',
                                'scenarios' => 'Founder Scenarios',
                                'contact' => 'Contact Info',
                                'social' => 'Social Links',
                                'cta' => 'Call to Action',
                                'general' => 'General',
                            ])
                            ->default('general')
                            ->required(),

                        Select::make('type')
                            ->options([
                                SiteSetting::TYPE_TEXT => 'Text / String',
                                SiteSetting::TYPE_BOOLEAN => 'Boolean (1 / 0)',
                                SiteSetting::TYPE_JSON => 'Structured JSON Payload',
                            ])
                            ->default(SiteSetting::TYPE_TEXT)
                            ->live()
                            ->required(),

                        TextInput::make('description')
                            ->maxLength(500),

                        Textarea::make('value')
                            ->label('Setting Value (Text or Boolean 1/0)')
                            ->rows(4)
                            ->visible(fn (Get $get): bool => $get('type') !== SiteSetting::TYPE_JSON)
                            ->dehydrated(fn (Get $get): bool => $get('type') !== SiteSetting::TYPE_JSON)
                            ->columnSpanFull(),

                        Textarea::make('json_value')
                            ->label('Structured JSON Payload (Pretty-Printed JSON)')
                            ->rows(12)
                            ->extraInputAttributes(['class' => 'font-mono text-xs'])
                            ->formatStateUsing(fn (mixed $state): string => is_array($state)
                                ? (string) json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
                                : (is_string($state) ? $state : ''))
                            ->dehydrateStateUsing(fn (mixed $state): ?array => is_array($state)
                                ? $state
                                : (filled($state) ? json_decode((string) $state, true) : null))
                            ->rules(['nullable', 'json'])
                            ->visible(fn (Get $get): bool => $get('type') === SiteSetting::TYPE_JSON)
                            ->dehydrated(fn (Get $get): bool => $get('type') === SiteSetting::TYPE_JSON)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->columns([
                TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono'),

                TextColumn::make('group')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('description')
                    ->limit(55),

                TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'brand' => 'Brand Identity',
                        'hero' => 'Hero Section',
                        'console' => 'Interactive Console',
                        'blueprint' => '10-Step Blueprint',
                        'scenarios' => 'Founder Scenarios',
                        'contact' => 'Contact Info',
                        'social' => 'Social Links',
                        'cta' => 'Call to Action',
                        'general' => 'General',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        SiteSetting::TYPE_TEXT => 'Text',
                        SiteSetting::TYPE_BOOLEAN => 'Boolean',
                        SiteSetting::TYPE_JSON => 'JSON',
                    ]),
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
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}

