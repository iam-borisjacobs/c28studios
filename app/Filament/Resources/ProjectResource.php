<?php

namespace App\Filament\Resources;

use App\Filament\Blocks\C28BuilderBlocks;
use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
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

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Engineering Content';

    protected static ?string $navigationLabel = 'Projects & Case Studies';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::query()->published()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('ProjectTabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Overview & Work Classification')
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

                                Select::make('work_type')
                                    ->label('Work Classification (AGENTS.md Integrity Rule)')
                                    ->options(Project::WORK_TYPES)
                                    ->helperText('Mandatory: Strictly distinguish Production Client Work from Personal / Studio Lab Demonstrations.')
                                    ->required(),

                                Select::make('service_id')
                                    ->label('Primary Associated Service')
                                    ->relationship('service', 'title')
                                    ->searchable()
                                    ->preload(),

                                TextInput::make('client_or_context')
                                    ->label('Client or Lab Context')
                                    ->placeholder('C28 Engineering Lab Reference Architecture')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Textarea::make('problem_summary')
                                    ->label('Engineering Problem Summary')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),

                                Textarea::make('solution_summary')
                                    ->label('C28 Solution Summary')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Metrics, Stack & Publishing')
                            ->columns(2)
                            ->schema([
                                Repeater::make('outcome_metrics')
                                    ->label('Verified Outcome Metrics')
                                    ->defaultItems(0)
                                    ->schema([
                                        TextInput::make('label')
                                            ->required()
                                            ->maxLength(120),
                                        TextInput::make('value')
                                            ->required()
                                            ->maxLength(60),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),

                                TagsInput::make('tech_stack')
                                    ->label('Technologies Used')
                                    ->placeholder('Add technology tag...')
                                    ->columnSpanFull(),

                                TextInput::make('repo_url')
                                    ->label('Repository URL')
                                    ->url()
                                    ->maxLength(255),

                                TextInput::make('live_url')
                                    ->label('Live Deployment URL')
                                    ->url()
                                    ->maxLength(255),

                                TextInput::make('featured_image')
                                    ->label('Featured Diagram / Image Path')
                                    ->maxLength(255),

                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Toggle::make('is_featured')
                                    ->label('Featured Case Study')
                                    ->default(true),

                                Toggle::make('is_published')
                                    ->label('Published')
                                    ->default(true),

                                DateTimePicker::make('published_at')
                                    ->default(now()),
                            ]),

                        Tabs\Tab::make('Architecture Steps & Code Artifacts')
                            ->schema([
                                Repeater::make('architecture_steps')
                                    ->label('Architecture Blueprint Steps')
                                    ->defaultItems(0)
                                    ->schema([
                                        TextInput::make('step')
                                            ->label('Step #')
                                            ->default('01')
                                            ->maxLength(10),
                                        TextInput::make('category')
                                            ->maxLength(80),
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(180),
                                        TextInput::make('deliverable')
                                            ->maxLength(180),
                                        Textarea::make('problem')
                                            ->rows(2),
                                        TextInput::make('outcome')
                                            ->maxLength(255),
                                        TextInput::make('cmd')
                                            ->label('Verification Command')
                                            ->extraInputAttributes(['class' => 'font-mono text-xs'])
                                            ->maxLength(255),
                                    ])
                                    ->columns(2)
                                    ->collapsible(),

                                Repeater::make('code_artifacts')
                                    ->label('Engineering Config / Code Artifacts (Dockerfile, nginx.conf, GitHub Actions)')
                                    ->defaultItems(0)
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->maxLength(160),
                                        Select::make('language')
                                            ->options([
                                                'yaml' => 'YAML',
                                                'dockerfile' => 'Dockerfile',
                                                'nginx' => 'Nginx',
                                                'bash' => 'Bash',
                                                'php' => 'PHP',
                                            ])
                                            ->default('yaml'),
                                        Textarea::make('code')
                                            ->rows(6)
                                            ->required()
                                            ->extraInputAttributes(['class' => 'font-mono text-xs'])
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('Visual Builder Blocks')
                            ->schema([
                                C28BuilderBlocks::make('builder_blocks', 'Case Study Dynamic Blocks'),
                            ]),
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

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(45),

                TextColumn::make('work_type')
                    ->label('Classification')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Project::WORK_TYPES[$state] ?? $state)
                    ->color(fn (string $state): string => $state === Project::WORK_TYPE_CLIENT_PRODUCTION ? 'success' : 'info'),

                TextColumn::make('service.title')
                    ->label('Service')
                    ->placeholder('—'),

                ToggleColumn::make('is_featured')
                    ->label('Featured'),

                ToggleColumn::make('is_published')
                    ->label('Published'),

                TextColumn::make('published_at')
                    ->dateTime('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('work_type')
                    ->options(Project::WORK_TYPES),
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'title'),
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
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}

