<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CafeOrderResource\Pages;
use App\Models\CafeOrder;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class CafeOrderResource extends Resource
{
    protected static ?string $model = CafeOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Cafe Management';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'ORD-' . strtoupper(uniqid()))
                            ->disabled(fn ($record) => $record !== null),

                        Forms\Components\Select::make('member_id')
                            ->label('Customer')
                            ->relationship('member', 'name')
                            ->searchable(['name', 'email'])
                            ->preload()
                            ->optionsLimit(50)
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Select::make('order_status')
                            ->label('Order Status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'preparing' => 'Preparing',
                                'ready' => 'Ready',
                                'served' => 'Served',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending')
                            ->live(),

                        Forms\Components\Select::make('payment_status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'partially_paid' => 'Partially Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending'),
                    ])->columns(2),

                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $product = \App\Models\CafeProduct::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $unitPrice = $get('unit_price') ?? 0;
                                        $set('total_price', $state * $unitPrice);
                                    }),

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('£')
                                    ->step(0.01)
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $quantity = $get('quantity') ?? 1;
                                        $set('total_price', $quantity * $state);
                                    }),

                                Forms\Components\TextInput::make('total_price')
                                    ->label('Total')
                                    ->required()
                                    ->numeric()
                                    ->prefix('£')
                                    ->step(0.01)
                                    ->disabled(),

                                Forms\Components\Textarea::make('customizations')
                                    ->label('Special Instructions')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(4)
                            ->reorderable(false)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                $state['product_id'] ?
                                    \App\Models\CafeProduct::find($state['product_id'])?->name ?? 'Product'
                                    : 'New Item'
                            ),
                    ]),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->required()
                            ->numeric()
                            ->prefix('£')
                            ->step(0.01),

                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Tax Amount')
                            ->numeric()
                            ->prefix('£')
                            ->step(0.01)
                            ->default(0),

                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Discount Amount')
                            ->numeric()
                            ->prefix('£')
                            ->step(0.01)
                            ->default(0),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->required()
                            ->numeric()
                            ->prefix('£')
                            ->step(0.01),
                    ])->columns(2),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Order Notes')
                            ->rows(3),

                        Forms\Components\Select::make('order_type')
                            ->label('Order Type')
                            ->options([
                                'dine_in' => 'Dine In',
                                'takeaway' => 'Takeaway',
                                'delivery' => 'Delivery',
                            ])
                            ->default('dine_in'),

                        Forms\Components\DateTimePicker::make('scheduled_for')
                            ->label('Scheduled For')
                            ->minDate(now()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('member.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\SelectColumn::make('order_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready' => 'Ready',
                        'served' => 'Served',
                        'cancelled' => 'Cancelled',
                    ])
                    ->selectablePlaceholder(false),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Payment')
                    ->colors([
                        'danger' => 'pending',
                        'success' => 'paid',
                        'warning' => 'partially_paid',
                        'secondary' => 'refunded',
                    ]),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('GBP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order_type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'primary' => 'dine_in',
                        'success' => 'takeaway',
                        'warning' => 'delivery',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'dine_in' => 'Dine In',
                        'takeaway' => 'Takeaway',
                        'delivery' => 'Delivery',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_for')
                    ->label('Scheduled')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('order_status')
                    ->label('Order Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready' => 'Ready',
                        'served' => 'Served',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'partially_paid' => 'Partially Paid',
                        'refunded' => 'Refunded',
                    ]),

                Tables\Filters\SelectFilter::make('order_type')
                    ->label('Order Type')
                    ->options([
                        'dine_in' => 'Dine In',
                        'takeaway' => 'Takeaway',
                        'delivery' => 'Delivery',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->label('Today\'s Orders')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereDate('created_at', today())
                    ),

                Tables\Filters\Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereBetween('created_at', [
                            now()->startOfWeek(),
                            now()->endOfWeek()
                        ])
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_receipt')
                    ->label('Print Receipt')
                    ->icon('heroicon-o-printer')
                    ->url(fn (CafeOrder $record): string => route('cafe.receipt', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')
                            ->label('Order Number'),
                        Infolists\Components\TextEntry::make('member.name')
                            ->label('Customer'),
                        Infolists\Components\TextEntry::make('order_status')
                            ->label('Order Status')
                            ->badge(),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge(),
                        Infolists\Components\TextEntry::make('order_type')
                            ->label('Order Type')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'dine_in' => 'Dine In',
                                'takeaway' => 'Takeaway',
                                'delivery' => 'Delivery',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime(),
                    ])->columns(3),

                Infolists\Components\Section::make('Order Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Product'),
                                Infolists\Components\TextEntry::make('quantity'),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('GBP'),
                                Infolists\Components\TextEntry::make('total_price')
                                    ->label('Total')
                                    ->money('GBP'),
                                Infolists\Components\TextEntry::make('customizations')
                                    ->label('Special Instructions')
                                    ->columnSpanFull(),
                            ])->columns(4),
                    ]),

                Infolists\Components\Section::make('Order Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->money('GBP'),
                        Infolists\Components\TextEntry::make('tax_amount')
                            ->label('Tax')
                            ->money('GBP'),
                        Infolists\Components\TextEntry::make('discount_amount')
                            ->label('Discount')
                            ->money('GBP'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total')
                            ->money('GBP')
                            ->weight('bold'),
                    ])->columns(4),

                Infolists\Components\Section::make('Additional Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Order Notes'),
                        Infolists\Components\TextEntry::make('scheduled_for')
                            ->label('Scheduled For')
                            ->dateTime(),
                    ])->columns(2),
            ]);
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
            'index' => Pages\ListCafeOrders::route('/'),
            'create' => Pages\CreateCafeOrder::route('/create'),
            'view' => Pages\ViewCafeOrder::route('/{record}'),
            'edit' => Pages\EditCafeOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('order_status', ['pending', 'confirmed', 'preparing'])
                               ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $activeOrders = static::getModel()::whereIn('order_status', ['pending', 'confirmed', 'preparing'])
                                          ->count();

        return $activeOrders > 0 ? 'warning' : null;
    }
}
