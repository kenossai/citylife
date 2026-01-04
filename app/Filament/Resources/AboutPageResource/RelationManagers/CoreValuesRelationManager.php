<?php

namespace App\Filament\Resources\AboutPageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoreValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'coreValues';

    protected static ?string $title = 'Core Values';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $context, $state, Forms\Set $set) {
                        if ($context === 'create') {
                            $set('slug', \Illuminate\Support\Str::slug($state));
                        }
                    })
                    ->helperText('The name of this core value'),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique('core_values', 'slug', ignoreRecord: true, modifyRuleUsing: function ($rule, $livewire) {
                        return $rule->where('about_page_id', $livewire->ownerRecord->id);
                    })
                    ->rules(['alpha_dash'])
                    ->helperText('URL-friendly version (auto-generated from title)'),

                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Detailed description of this core value'),

                Forms\Components\Textarea::make('bible_verse')
                    ->maxLength(500)
                    ->helperText('Bible verse related to this core value'),

                Forms\Components\TextInput::make('bible_reference')
                    ->maxLength(255)
                    ->helperText('Bible verse reference (e.g., John 3:16)'),

                Forms\Components\TextInput::make('featured_image')
                    ->maxLength(255)
                    ->helperText('Featured image path (S3 URL or path)'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true)
                    ->helperText('Whether this core value is active and visible'),

                Forms\Components\Toggle::make('is_featured')
                    ->default(false)
                    ->helperText('Whether this core value should be featured prominently'),

                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Order for sorting (lower numbers appear first)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('short_description')
                    ->label('Description')
                    ->limit(50)
                    ->default('—')
                    ->wrap(),

                Tables\Columns\TextColumn::make('bible_reference')
                    ->badge()
                    ->color('primary')
                    ->default('—'),                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-outline-star')
                    ->trueColor('warning'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Core Value'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order');
    }
}
