<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Models\Inquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationGroup = 'Lead & Client Pipeline';

    protected static ?string $navigationLabel = 'Consultation Inquiries';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        try {
            $count = Inquiry::query()->unread()->count();

            return $count > 0 ? (string) $count : null;
        } catch (\Throwable) {
            return null;
        }
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Internal Studio Pipeline & Triage')
                    ->description('Update workflow status and record private engineering scoping notes.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Pipeline Status')
                            ->options(Inquiry::STATUSES)
                            ->required()
                            ->default(Inquiry::STATUS_NEW),

                        Forms\Components\DateTimePicker::make('responded_at')
                            ->label('Responded At'),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Internal Engineering & Architecture Scoping Notes')
                            ->rows(4)
                            ->placeholder('Document technical findings, proposed sprint tier, server constraints, or follow-up steps...')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Submitted Prospect & Architecture Dossier')
                    ->description('Prospect submission details captured from the public C28 consultation intake.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Prospect Name & Role')
                            ->required()
                            ->maxLength(120)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('email')
                            ->label('Work Email')
                            ->email()
                            ->required()
                            ->maxLength(190)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('company')
                            ->label('Company / Project')
                            ->maxLength(150)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('service_interest')
                            ->label('Primary Engineering Service')
                            ->required()
                            ->maxLength(120)
                            ->disabledOn('edit'),

                        Forms\Components\Select::make('engagement_mode')
                            ->label('Engagement Mode')
                            ->options(Inquiry::ENGAGEMENT_MODES)
                            ->default(Inquiry::MODE_SPRINT_FIXED)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('project_type')
                            ->label('Project Type')
                            ->maxLength(80)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('budget_range')
                            ->label('Target Budget Range')
                            ->maxLength(80)
                            ->disabledOn('edit'),

                        Forms\Components\TextInput::make('timeline')
                            ->label('Target Timeline')
                            ->maxLength(80)
                            ->disabledOn('edit'),

                        Forms\Components\Textarea::make('message')
                            ->label('Repository, Infrastructure & Project Context')
                            ->rows(5)
                            ->required()
                            ->disabledOn('edit')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('ip_address')
                            ->label('Client IP Address')
                            ->disabled(),

                        Forms\Components\TextInput::make('user_agent')
                            ->label('User Agent')
                            ->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Inquiry::STATUSES[$state] ?? ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        Inquiry::STATUS_NEW => 'success',
                        Inquiry::STATUS_IN_REVIEW => 'warning',
                        Inquiry::STATUS_RESPONDED => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('name')
                    ->label('Prospect')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Inquiry $record): string => $record->company ?: 'Independent / Unspecified'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('service_interest')
                    ->label('Service Tier')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('engagement_mode')
                    ->label('Mode')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Inquiry::ENGAGEMENT_MODES[$state] ?? $state)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('budget_range')
                    ->label('Budget')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Inquiry::STATUSES),

                Tables\Filters\SelectFilter::make('engagement_mode')
                    ->options(Inquiry::ENGAGEMENT_MODES),
            ])
            ->actions([
                Tables\Actions\Action::make('markResponded')
                    ->label('Mark Responded')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Inquiry $record): bool => $record->status !== Inquiry::STATUS_RESPONDED)
                    ->action(function (Inquiry $record): void {
                        $record->update([
                            'status' => Inquiry::STATUS_RESPONDED,
                            'responded_at' => now(),
                        ]);
                    }),

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
            'index' => Pages\ListInquiries::route('/'),
            'create' => Pages\CreateInquiry::route('/create'),
            'edit' => Pages\EditInquiry::route('/{record}/edit'),
        ];
    }
}

