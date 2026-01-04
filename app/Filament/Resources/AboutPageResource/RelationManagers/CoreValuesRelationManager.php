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

                Forms\Components\FileUpload::make('featured_image')
                    ->image()
                    ->disk('s3')
                    ->visibility('public')
                    ->directory('core-values')
                    ->maxSize(5120)
                    ->helperText('Featured image for this core value'),

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
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
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
            ->defaultSort('sort_order', 'asc');
    }
}
