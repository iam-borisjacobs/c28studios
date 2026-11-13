<?php

namespace App\Filament\Blocks;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class C28BuilderBlocks
{
    /**
     * Build the reusable C28 Filament Builder / NioBuilder block library.
     */
    public static function make(
        string $fieldName = 'builder_blocks',
        string $label = 'Dynamic Content Blocks'
    ): Builder {
        return Builder::make($fieldName)
            ->label($label)
            ->collapsible()
            ->cloneable()
            ->reorderableWithButtons()
            ->blockNumbers(false)
            ->blocks(static::blocks());
    }

    /**
     * @return array<int, Block>
     */
    public static function blocks(): array
    {
        return [
            Block::make('hero_terminal')
                ->label('Hero / Terminal Stream')
                ->icon('heroicon-o-command-line')
                ->schema([
                    TextInput::make('window_title')
                        ->label('Terminal Window Title')
                        ->default('c28-control-plane / production-delivery-pipeline.yml')
                        ->required()
                        ->maxLength(160),
                    TextInput::make('ssh_target')
                        ->label('SSH / Host Label')
                        ->default('ssh deploy@prod-node-01')
                        ->maxLength(120),
                    TextInput::make('status_badge')
                        ->label('Status Badge')
                        ->default('PRODUCTION HEALTHY • 99.98% UPTIME')
                        ->maxLength(120),
                    Repeater::make('logs')
                        ->label('Terminal Log Lines')
                        ->schema([
                            TextInput::make('timestamp')
                                ->label('Prefix / Timestamp')
                                ->default('[12:04:01]')
                                ->maxLength(40),
                            TextInput::make('message')
                                ->label('Log Message')
                                ->required()
                                ->maxLength(300),
                            TextInput::make('status')
                                ->label('Status Tag')
                                ->default('OK')
                                ->maxLength(40),
                        ])
                        ->columns(3)
                        ->defaultItems(1),
                    Textarea::make('outcome_summary')
                        ->label('Outcome Summary')
                        ->rows(2),
                ]),

            Block::make('architecture_step_list')
                ->label('10-Step Architecture Sequence')
                ->icon('heroicon-o-queue-list')
                ->schema([
                    TextInput::make('section_heading')
                        ->label('Section Heading')
                        ->default('From Local Application To Hardened Production')
                        ->maxLength(200),
                    Repeater::make('steps')
                        ->label('Architecture Steps')
                        ->schema([
                            TextInput::make('step')
                                ->label('Step #')
                                ->default('01')
                                ->required()
                                ->maxLength(10),
                            TextInput::make('category')
                                ->label('Category')
                                ->default('CONTAINERIZATION')
                                ->maxLength(80),
                            TextInput::make('title')
                                ->label('Step Title')
                                ->required()
                                ->maxLength(180),
                            TextInput::make('deliverable')
                                ->label('Deliverable Badge')
                                ->maxLength(180),
                            Textarea::make('problem')
                                ->label('Problem / Context')
                                ->rows(2),
                            TextInput::make('outcome')
                                ->label('Verified Outcome')
                                ->maxLength(250),
                            TextInput::make('cmd')
                                ->label('Verification Command')
                                ->extraInputAttributes(['class' => 'font-mono text-xs'])
                                ->maxLength(250),
                        ])
                        ->columns(2)
                        ->collapsible(),
                ]),

            Block::make('code_artifact_viewer')
                ->label('Engineering Config / Code Artifact')
                ->icon('heroicon-o-code-bracket-square')
                ->schema([
                    TextInput::make('title')
                        ->label('Artifact Filename / Title')
                        ->placeholder('.github/workflows/deploy-production.yml')
                        ->required()
                        ->maxLength(160),
                    Select::make('language')
                        ->label('Syntax Language')
                        ->options([
                            'yaml' => 'YAML (GitHub Actions / Compose)',
                            'dockerfile' => 'Dockerfile',
                            'nginx' => 'Nginx Config',
                            'bash' => 'Bash / Shell',
                            'php' => 'PHP / Laravel',
                            'javascript' => 'JavaScript',
                            'json' => 'JSON',
                        ])
                        ->default('yaml')
                        ->required(),
                    Textarea::make('code')
                        ->label('Code / Config Contents')
                        ->rows(8)
                        ->required()
                        ->extraInputAttributes(['class' => 'font-mono text-xs']),
                    TextInput::make('caption')
                        ->label('Engineering Note / Caption')
                        ->maxLength(250),
                ]),

            Block::make('metric_callout_grid')
                ->label('Outcome Metric Callout Grid')
                ->icon('heroicon-o-chart-bar')
                ->schema([
                    TextInput::make('heading')
                        ->label('Grid Heading')
                        ->maxLength(160),
                    Repeater::make('metrics')
                        ->label('Metrics')
                        ->schema([
                            TextInput::make('value')
                                ->label('Metric Value (e.g. 0m, 99.98%, -32%)')
                                ->required()
                                ->maxLength(60),
                            TextInput::make('label')
                                ->label('Metric Label')
                                ->required()
                                ->maxLength(120),
                            TextInput::make('note')
                                ->label('Context Note')
                                ->maxLength(160),
                        ])
                        ->columns(3)
                        ->defaultItems(2),
                ]),

            Block::make('problem_solution_split')
                ->label('Problem vs. C28 Solution Split')
                ->icon('heroicon-o-arrows-right-left')
                ->schema([
                    TextInput::make('badge')
                        ->label('Section Badge')
                        ->default('OUTCOME-FIRST ENGINEERING')
                        ->maxLength(100),
                    TextInput::make('problem_heading')
                        ->label('Problem Heading')
                        ->default('Bottleneck Before C28')
                        ->maxLength(160),
                    Textarea::make('problem')
                        ->label('Problem Description')
                        ->rows(3)
                        ->required(),
                    TextInput::make('solution_heading')
                        ->label('Solution Heading')
                        ->default('C28 Engineered Solution')
                        ->maxLength(160),
                    Textarea::make('solution')
                        ->label('Solution Description')
                        ->rows(3)
                        ->required(),
                    TextInput::make('verification_cmd')
                        ->label('Verification Command (Optional)')
                        ->extraInputAttributes(['class' => 'font-mono text-xs'])
                        ->maxLength(200),
                ]),

            Block::make('markdown_prose')
                ->label('Structured Engineering Prose Section')
                ->icon('heroicon-o-document-text')
                ->schema([
                    TextInput::make('heading')
                        ->label('Section Heading')
                        ->maxLength(200),
                    Textarea::make('body')
                        ->label('Prose / Markdown Content')
                        ->rows(5)
                        ->required(),
                ]),

            Block::make('cta_banner')
                ->label('Call-to-Action Banner')
                ->icon('heroicon-o-bolt')
                ->schema([
                    TextInput::make('badge')
                        ->label('Badge Label')
                        ->default('C28 — SOFTWARE & CLOUD ENGINEERING')
                        ->maxLength(120),
                    TextInput::make('heading')
                        ->label('Banner Heading')
                        ->required()
                        ->maxLength(200),
                    Textarea::make('subtext')
                        ->label('Supporting Copy')
                        ->rows(2),
                    TextInput::make('button_label')
                        ->label('CTA Button Label')
                        ->default('Book a Consultation')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('button_url')
                        ->label('CTA Target URL or Anchor')
                        ->default('#consultation')
                        ->maxLength(200),
                ]),
        ];
    }
}

