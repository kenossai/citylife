<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BecomingSectionResource\Pages;
use App\Filament\Resources\BecomingSectionResource\RelationManagers;
use App\Models\BecomingSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BecomingSectionResource extends Resource
{
    protected static ?string $model = BecomingSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Becoming Section';

    protected static ?string $modelLabel = 'Becoming Section';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Content Settings')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('tagline')
                                    ->label('Tagline')
                                    ->required()
                                    ->default('Are You Ready to Make a Difference?')
                                    ->columnSpan(2),
                                    
                                Forms\Components\TextInput::make('title')
                                    ->label('Main Title')
                                    ->required()
                                    ->default('Inspiring and Helping for Better'),
                                    
                                Forms\Components\TextInput::make('title_highlight')
                                    ->label('Highlighted Title Part')
                                    ->required()
                                    ->default('Lifestyle'),
                            ]),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(4)
                            ->default('Dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernaturaut odit aut fugit, sed quia consequuntur. Dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas.'),
                    ]),

                Forms\Components\Section::make('Action Buttons')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('volunteer_title')
                                    ->label('Volunteer Button Text')
                                    ->required()
                                    ->default('Become A Volunteer'),
                                    
                                Forms\Components\TextInput::make('volunteer_icon')
                                    ->label('Volunteer Icon Class')
                                    ->required()
                                    ->default('icon-unity'),
                                    
                                Forms\Components\TextInput::make('new_member_title')
                                    ->label('New Member Button Text')
                                    ->required()
                                    ->default("I'm New Here"),
                                    
                                Forms\Components\TextInput::make('new_member_icon')
                                    ->label('New Member Icon Class')
                                    ->required()
                                    ->default('icon-healthcare'),
                            ]),
                    ]),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\FileUpload::make('background_image')
                                    ->label('Background Image')
                                    ->image()
                                    ->directory('becoming/backgrounds'),
                                    
                                Forms\Components\FileUpload::make('left_image')
                                    ->label('Left Image')
                                    ->image()
                                    ->directory('becoming/images'),
                                    
                                Forms\Components\FileUpload::make('right_image')
                                    ->label('Right Image')
                                    ->image()
                                    ->directory('becoming/images'),
                            ]),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Only one section can be active at a time'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tagline')
                    ->label('Tagline')
                    ->searchable()
                    ->limit(50),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->formatStateUsing(fn ($record) => $record->title . ' ' . $record->title_highlight),
                    
                Tables\Columns\TextColumn::make('volunteer_title')
                    ->label('Volunteer Button')
                    ->limit(30),
                    
                Tables\Columns\TextColumn::make('new_member_title')
                    ->label('New Member Button')
                    ->limit(30),
                    
                Tables\Columns\BooleanColumn::make('is_active')
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Active Only'),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBecomingSections::route('/'),
            'create' => Pages\CreateBecomingSection::route('/create'),
            'edit' => Pages\EditBecomingSection::route('/{record}/edit'),
        ];
    }
}
