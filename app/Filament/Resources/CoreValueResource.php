<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoreValueResource\Pages;
use App\Filament\Resources\CoreValueResource\RelationManagers;
use App\Models\CoreValue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoreValueResource extends Resource
{
    protected static ?string $model = CoreValue::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Core Values';

    protected static ?string $modelLabel = 'Core Value';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('about_page_id')
                            ->relationship('aboutPage', 'title')
                            ->required()
                            ->default(fn () => \App\Models\AboutPage::first()?->id)
                            ->helperText('Select which about page this core value belongs to'),
                    ])->columns(2),

                Forms\Components\Section::make('Content')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Full description of the core value'),

                        Forms\Components\Textarea::make('short_description')
                            ->maxLength(500)
                            ->helperText('Brief summary (optional, will use truncated description if not provided)'),
                    ]),

                Forms\Components\Section::make('Biblical Foundation')
                    ->schema([
                        Forms\Components\Textarea::make('bible_verse')
                            ->rows(3)
                            ->helperText('Bible verse that supports this core value'),

                        Forms\Components\TextInput::make('bible_reference')
                            ->maxLength(255)
                            ->helperText('Bible verse reference (e.g., John 3:16)'),
                    ])->columns(2),

                Forms\Components\Section::make('Visual & Styling')
                    ->schema([
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255)
                            ->helperText('Icon class (e.g., heroicon-o-heart)'),

                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->disk('s3')
                            ->visibility('public')
                            ->directory('core-values')
                            ->helperText('Optional featured image'),

                        Forms\Components\ColorPicker::make('background_color')
                            ->helperText('Background color for the value card'),

                        Forms\Components\ColorPicker::make('text_color')
                            ->helperText('Text color for the value card'),
                    ])->columns(2),

                Forms\Components\Section::make('Settings & SEO')
                    ->schema([
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

                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(255)
                            ->helperText('SEO meta title'),

                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(160)
                            ->helperText('SEO meta description (max 160 characters)'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('aboutPage.title')
                    ->label('About Page')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('excerpt')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('bible_reference')
                    ->badge()
                    ->color('success')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
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

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('about_page_id')
                    ->relationship('aboutPage', 'title')
                    ->label('About Page'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoreValues::route('/'),
            'create' => Pages\CreateCoreValue::route('/create'),
            'edit' => Pages\EditCoreValue::route('/{record}/edit'),
        ];
    }
}
