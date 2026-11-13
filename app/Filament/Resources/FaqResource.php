<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Site Configuration';

    protected static ?string $navigationLabel = 'Founder FAQs';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->active()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('FAQ Entry')
                    ->columns(2)
                    ->schema([
                        TextInput::make('question')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Select::make('category')
                            ->options([
                                'general' => 'General Studio',
                                'devops' => 'DevOps & Cloud',
                                'devsecops' => 'DevSecOps & Security',
                                'ownership' => 'Code & Server Ownership',
                                'engagement' => 'Project Engagement',
                            ])
                            ->default('general')
                            ->required(),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Textarea::make('answer')
                            ->rows(4)
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active on Public FAQ Section')
                            ->default(true),
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

                TextColumn::make('question')
                    ->searchable()
                    ->limit(65),

                TextColumn::make('category')
                    ->badge()
                    ->color('gray'),

                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'general' => 'General Studio',
                        'devops' => 'DevOps & Cloud',
                        'devsecops' => 'DevSecOps & Security',
                        'ownership' => 'Code & Server Ownership',
                        'engagement' => 'Project Engagement',
                    ]),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}

