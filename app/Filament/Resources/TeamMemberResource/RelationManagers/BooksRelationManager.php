<?php

namespace App\Filament\Resources\TeamMemberResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BooksRelationManager extends RelationManager
{
    protected static string $relationship = 'books';

    protected static ?string $title = 'Books Written';

    protected static ?string $icon = 'heroicon-o-book-open';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Book Information')
                    ->schema([
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
                            ->inline(false),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(60),

                        Forms\Components\Textarea::make('meta_description')
                            ->maxLength(160)
                            ->rows(2),
                    ])->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('cover_image')
                        ->label('')
                        ->size(180)
                        ->height(240)
                        ->defaultImageUrl(fn () => 'https://via.placeholder.com/180x240?text=No+Cover')
                        ->extraImgAttributes(['class' => 'rounded-lg shadow-lg']),

                    Tables\Columns\TextColumn::make('title')
                        ->weight('bold')
                        ->size('sm')
                        ->alignment('center')
                        ->wrap(),
                ]),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
                '2xl' => 4,
            ])
            ->paginated(false)
            ->filters([
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

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Slug is auto-generated in the model, but we can ensure it's set
                        if (empty($data['slug']) && !empty($data['title'])) {
                            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
                        }
                        return $data;
                    }),
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
            ->emptyStateHeading('No books yet')
            ->emptyStateDescription('Add the first book written by this team member.')
            ->emptyStateIcon('heroicon-o-book-open');
    }
}
