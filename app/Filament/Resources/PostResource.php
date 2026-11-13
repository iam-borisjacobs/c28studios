<?php

namespace App\Filament\Resources;

use App\Filament\Blocks\C28BuilderBlocks;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    protected static ?string $navigationGroup = 'Engineering Content';

    protected static ?string $navigationLabel = 'Lab Notes & Walkthroughs';

    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->published()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Article Identity & Category Classification')
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

                        Select::make('category')
                            ->label('C28 Content Category (5 Supported Categories)')
                            ->options(Post::CATEGORIES)
                            ->required(),

                        Select::make('work_type')
                            ->label('Work Classification (AGENTS.md Integrity Rule)')
                            ->options(Post::WORK_TYPES)
                            ->default(Post::WORK_TYPE_LAB_DEMONSTRATION)
                            ->required(),

                        TextInput::make('hook_text')
                            ->label('Terminal / Card Hook Preview')
                            ->placeholder('$ docker compose up -d -> Nginx SSL 443 Active')
                            ->extraInputAttributes(['class' => 'font-mono text-xs'])
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Textarea::make('excerpt')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('reading_time_minutes')
                            ->label('Reading Time (Minutes)')
                            ->numeric()
                            ->default(5)
                            ->minValue(1)
                            ->required(),

                        TextInput::make('video_url')
                            ->label('Optional Walkthrough Video URL')
                            ->url()
                            ->maxLength(255),

                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),

                        DateTimePicker::make('published_at')
                            ->default(now()),
                    ]),

                Section::make('Structured Code Snippets')
                    ->schema([
                        Repeater::make('code_snippets')
                            ->label('Associated Terminal / Config Snippets')
                            ->defaultItems(0)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(160),
                                Select::make('language')
                                    ->options([
                                        'bash' => 'Bash / Shell',
                                        'yaml' => 'YAML',
                                        'dockerfile' => 'Dockerfile',
                                        'nginx' => 'Nginx',
                                        'php' => 'PHP / Blade',
                                        'blade' => 'Blade',
                                    ])
                                    ->default('bash'),
                                Textarea::make('code')
                                    ->rows(4)
                                    ->required()
                                    ->extraInputAttributes(['class' => 'font-mono text-xs'])
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ]),

                Section::make('Article Content Blocks (Filament Builder)')
                    ->schema([
                        C28BuilderBlocks::make('content_blocks', 'Article Content Blocks'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(45),

                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Post::CATEGORIES[$state] ?? $state)
                    ->color('primary'),

                TextColumn::make('work_type')
                    ->label('Work Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Post::WORK_TYPES[$state] ?? $state)
                    ->color(fn (string $state): string => $state === Post::WORK_TYPE_CLIENT_PRODUCTION ? 'success' : 'info'),

                TextColumn::make('reading_time_minutes')
                    ->label('Read Time')
                    ->suffix(' min'),

                ToggleColumn::make('is_published')
                    ->label('Published'),

                TextColumn::make('published_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(Post::CATEGORIES),
                SelectFilter::make('work_type')
                    ->options(Post::WORK_TYPES),
                TernaryFilter::make('is_published'),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
