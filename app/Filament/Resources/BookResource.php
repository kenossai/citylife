<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Books';

    protected static ?string $modelLabel = 'Book';

    protected static ?string $navigationGroup = 'Team Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $policy = \App\Policies\BookPolicy::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Book Information')
                ->schema([
                    Forms\Components\Select::make('team_member_id')
                        ->label('Author')
                        ->relationship('teamMember', 'first_name')
                        ->getOptionLabelFromRecordUsing(fn (TeamMember $record) => $record->full_name)
                        ->searchable(['first_name', 'last_name'])
                        ->preload()
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('subtitle')
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\RichEditor::make('description')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'link', 'bulletList', 'orderedList', 'blockquote'
                        ])
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('short_description')
                        ->maxLength(500)
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Publishing Details')
                ->schema([
                    Forms\Components\TextInput::make('publisher')
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('published_date')
                        ->label('Publication Date'),

                    Forms\Components\TextInput::make('edition')
                        ->maxLength(255),

                    Forms\Components\Select::make('language')
                        ->options([
                            'English' => 'English',
                            'Spanish' => 'Spanish',
                            'French' => 'French',
                            'German' => 'German',
                            'Chinese' => 'Chinese',
                            'Portuguese' => 'Portuguese',
                        ])
                        ->default('English')
                        ->required(),

                    Forms\Components\TextInput::make('isbn')
                        ->label('ISBN')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('isbn13')
                        ->label('ISBN-13')
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('pages')
                        ->numeric()
                        ->minValue(1)
                        ->label('Number of Pages'),

                    Forms\Components\Select::make('format')
                        ->options([
                            'hardcover' => 'Hardcover',
                            'paperback' => 'Paperback',
                            'ebook' => 'E-book',
                            'audiobook' => 'Audiobook',
                        ])
                        ->default('paperback')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Media')
                ->schema([
                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Book Cover')
                        ->image()
                        ->disk('s3')
                        ->visibility('public')
                        ->directory('books/covers')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('3:4')
                        ->imageEditor()
                        ->columnSpan(1),

                    Forms\Components\FileUpload::make('back_cover_image')
                        ->label('Back Cover')
                        ->image()
                        ->disk('s3')
                        ->visibility('public')
                        ->directory('books/covers')
                        ->imageEditor()
                        ->columnSpan(1),

                    Forms\Components\FileUpload::make('sample_pages')
                        ->label('Sample Pages')
                        ->multiple()
                        ->image()
                        ->disk('s3')
                        ->visibility('public')
                        ->directory('books/samples')
                        ->maxFiles(10)
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Pricing & Links')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->numeric()
                        ->prefix('£')
                        ->minValue(0)
                        ->step(0.01),

                    Forms\Components\Select::make('currency')
                        ->options([
                            'GBP' => 'GBP (£)',
                            'USD' => 'USD ($)',
                            'EUR' => 'EUR (€)',
                        ])
                        ->default('GBP')
                        ->required(),

                    Forms\Components\TextInput::make('purchase_link')
                        ->label('Purchase Link')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://example.com/purchase'),

                    Forms\Components\TextInput::make('amazon_link')
                        ->label('Amazon Link')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://amazon.com/...'),

                    Forms\Components\TextInput::make('preview_link')
                        ->label('Preview Link')
                        ->url()
                        ->maxLength(255)
                        ->placeholder('https://example.com/preview'),
                ])->columns(2),

            Forms\Components\Section::make('Categories & Topics')
                ->schema([
                    Forms\Components\Select::make('category')
                        ->options([
                            'Theology' => 'Theology',
                            'Biography' => 'Biography',
                            'Devotional' => 'Devotional',
                            'Biblical Studies' => 'Biblical Studies',
                            'Christian Living' => 'Christian Living',
                            'Prayer' => 'Prayer',
                            'Worship' => 'Worship',
                            'Leadership' => 'Leadership',
                            'Evangelism' => 'Evangelism',
                            'Family & Relationships' => 'Family & Relationships',
                            'Youth Ministry' => 'Youth Ministry',
                            'Church History' => 'Church History',
                        ])
                        ->searchable(),

                    Forms\Components\TagsInput::make('tags')
                        ->placeholder('Add tags...'),

                    Forms\Components\TagsInput::make('topics')
                        ->placeholder('Add topics covered...')
                        ->helperText('Topics covered in the book'),
                ])->columns(1),

            Forms\Components\Section::make('Display Settings')
                ->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->inline(false),

                    Forms\Components\Toggle::make('is_featured')
                        ->label('Featured')
                        ->default(false)
                        ->inline(false)
                        ->helperText('Featured books appear prominently on the website'),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first'),
                ])->columns(3),

            Forms\Components\Section::make('SEO & Metadata')
                ->schema([
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                            if (!$state && $record && $record->title) {
                                $component->state(\Illuminate\Support\Str::slug($record->title));
                            }
                        })
                        ->helperText('URL-friendly version of the title'),

                    Forms\Components\TextInput::make('meta_title')
                        ->maxLength(60)
                        ->helperText('SEO title (60 characters max)'),

                    Forms\Components\Textarea::make('meta_description')
                        ->maxLength(160)
                        ->rows(2)
                        ->helperText('SEO description (160 characters max)'),

                    Forms\Components\TagsInput::make('meta_keywords')
                        ->placeholder('Add SEO keywords...'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->circular()
                    ->defaultImageUrl(fn () => 'https://via.placeholder.com/50x75?text=No+Cover'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('author.full_name')
                    ->label('Author')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('format')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hardcover' => 'success',
                        'paperback' => 'info',
                        'ebook' => 'warning',
                        'audiobook' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('published_date')
                    ->label('Published')
                    ->date('M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('GBP')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('team_member_id')
                    ->label('Author')
                    ->relationship('teamMember', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn (TeamMember $record) => $record->full_name)
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Theology' => 'Theology',
                        'Biography' => 'Biography',
                        'Devotional' => 'Devotional',
                        'Biblical Studies' => 'Biblical Studies',
                        'Christian Living' => 'Christian Living',
                        'Prayer' => 'Prayer',
                        'Worship' => 'Worship',
                        'Leadership' => 'Leadership',
                        'Evangelism' => 'Evangelism',
                        'Family & Relationships' => 'Family & Relationships',
                        'Youth Ministry' => 'Youth Ministry',
                        'Church History' => 'Church History',
                    ]),

                Tables\Filters\SelectFilter::make('format')
                    ->options([
                        'hardcover' => 'Hardcover',
                        'paperback' => 'Paperback',
                        'ebook' => 'E-book',
                        'audiobook' => 'Audiobook',
                    ]),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
