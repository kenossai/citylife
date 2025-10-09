<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CafeProductResource\Pages;
use App\Models\CafeProduct;
use App\Models\CafeCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CafeProductResource extends Resource
{
    protected static ?string $model = CafeProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Cafe Management';

    protected static ?string $navigationLabel = 'Products';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => 
                                $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash']),

                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(1000),

                        Forms\Components\Textarea::make('ingredients')
                            ->rows(2)
                            ->maxLength(500)
                            ->helperText('List main ingredients separated by commas'),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Stock')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('£')
                            ->step(0.01)
                            ->minValue(0),

                        Forms\Components\TextInput::make('cost_price')
                            ->label('Cost Price')
                            ->numeric()
                            ->prefix('£')
                            ->step(0.01)
                            ->minValue(0)
                            ->helperText('Optional: for profit calculations'),

                        Forms\Components\Toggle::make('track_stock')
                            ->label('Track Stock')
                            ->live(),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Stock Quantity')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn (Forms\Get $get) => $get('track_stock'))
                            ->required(fn (Forms\Get $get) => $get('track_stock')),
                    ])->columns(2),

                Forms\Components\Section::make('Product Details')
                    ->schema([
                        Forms\Components\Select::make('size')
                            ->options([
                                'small' => 'Small',
                                'medium' => 'Medium',
                                'large' => 'Large',
                            ])
                            ->placeholder('Select size if applicable'),

                        Forms\Components\Select::make('temperature')
                            ->options([
                                'hot' => 'Hot',
                                'cold' => 'Cold',
                                'room_temp' => 'Room Temperature',
                            ])
                            ->placeholder('Select temperature'),

                        Forms\Components\TextInput::make('preparation_time')
                            ->label('Preparation Time (minutes)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(60),

                        Forms\Components\TagsInput::make('dietary_info')
                            ->label('Dietary Information')
                            ->placeholder('Add dietary tags')
                            ->suggestions([
                                'vegan',
                                'vegetarian',
                                'gluten_free',
                                'dairy_free',
                                'sugar_free',
                                'halal',
                                'kosher',
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Nutritional Information')
                    ->schema([
                        Forms\Components\Repeater::make('nutritional_info')
                            ->label('Nutritional Information')
                            ->schema([
                                Forms\Components\TextInput::make('nutrient')
                                    ->label('Nutrient')
                                    ->placeholder('e.g., Calories, Protein, Fat')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Value')
                                    ->placeholder('e.g., 250kcal, 10g')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->collapsed(),
                    ]),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Product Image')
                            ->image()
                            ->directory('cafe/products')
                            ->imageEditor(),

                        Forms\Components\FileUpload::make('gallery')
                            ->label('Gallery Images')
                            ->image()
                            ->multiple()
                            ->directory('cafe/products/gallery')
                            ->reorderable(),
                    ])->columns(1),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_available')
                            ->label('Available')
                            ->default(true),

                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured Product'),

                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->square()
                    ->size(60),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('GBP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state === null => 'gray',
                        $state <= 0 => 'danger',
                        $state <= 5 => 'warning',
                        default => 'success',
                    })
                    ->formatStateUsing(fn ($state, $record) => 
                        $record->track_stock ? ($state ?? 'N/A') : 'Not tracked'
                    ),

                Tables\Columns\IconColumn::make('is_available')
                    ->label('Available')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Availability')
                    ->placeholder('All products')
                    ->trueLabel('Available only')
                    ->falseLabel('Unavailable only'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All products')
                    ->trueLabel('Featured only')
                    ->falseLabel('Regular only'),

                Tables\Filters\Filter::make('low_stock')
                    ->label('Low Stock')
                    ->query(fn (Builder $query): Builder => 
                        $query->where('track_stock', true)
                              ->where('stock_quantity', '<=', 5)
                    ),
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
            ->defaultSort('sort_order');
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
            'index' => Pages\ListCafeProducts::route('/'),
            'create' => Pages\CreateCafeProduct::route('/create'),
            'edit' => Pages\EditCafeProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('track_stock', true)
                               ->where('stock_quantity', '<=', 5)
                               ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $lowStockCount = static::getModel()::where('track_stock', true)
                                           ->where('stock_quantity', '<=', 5)
                                           ->count();
        
        return $lowStockCount > 0 ? 'warning' : null;
    }
}
