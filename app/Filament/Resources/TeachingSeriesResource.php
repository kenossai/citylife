<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingSeriesResource\Pages;
use App\Filament\Resources\TeachingSeriesResource\RelationManagers;
use App\Models\TeachingSeries;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\SermonNoteMail;
use App\Mail\CustomSermonNoteMail;
use App\Models\Member;

class TeachingSeriesResource extends Resource
{
    protected static ?string $model = TeachingSeries::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Teaching Series';

    protected static ?string $modelLabel = 'Teaching Series';

    protected static ?string $pluralModelLabel = 'Teaching Series';

    protected static ?string $navigationGroup = 'Media Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Teaching Series Details')
                    ->tabs([
                        Tabs\Tab::make('Basic Information')
                            ->schema([
                                Section::make('Series Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            ),

                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(TeachingSeries::class, 'slug', ignoreRecord: true)
                                            ->rules(['alpha_dash']),

                                        Forms\Components\Select::make('pastor')
                                            ->options([
                                                'Pastor John Smith' => 'Pastor John Smith',
                                                'Pastor Mary Johnson' => 'Pastor Mary Johnson',
                                                'Pastor David Wilson' => 'Pastor David Wilson',
                                                'Guest Speaker' => 'Guest Speaker',
                                            ])
                                            ->searchable()
                                            ->allowHtml(false)
                                            ->preload(),

                                        Forms\Components\Select::make('category')
                                            ->options([
                                                'Sermons' => 'Sermons',
                                                'Bible Study' => 'Bible Study',
                                                'Devotional' => 'Devotional',
                                                'Worship' => 'Worship',
                                                'Youth' => 'Youth',
                                                'Family' => 'Family',
                                                'Evangelism' => 'Evangelism',
                                                'Prayer' => 'Prayer',
                                                'Discipleship' => 'Discipleship',
                                            ])
                                            ->searchable()
                                            ->preload(),
                                    ])
                                    ->columns(2),

                                Section::make('Content')
                                    ->schema([
                                        Forms\Components\Textarea::make('summary')
                                            ->label('Short Summary')
                                            ->maxLength(500)
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Forms\Components\RichEditor::make('description')
                                            ->label('Full Description')
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('scripture_references')
                                            ->label('Scripture References')
                                            ->placeholder('e.g., John 3:16, Romans 8:28, Philippians 4:13')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make('Media & Resources')
                            ->schema([
                                Section::make('Image')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->label('Series Image')
                                            ->image()
                                            ->directory('teaching-series')
                                            ->disk('public')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Media Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('video_url')
                                            ->label('Video URL')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://youtube.com/watch?v=...'),

                                        Forms\Components\TextInput::make('audio_url')
                                            ->label('Audio URL')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://soundcloud.com/...'),

                        Forms\Components\FileUpload::make('sermon_notes')
                            ->label('Sermon Notes (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('teaching-series/notes')
                            ->disk('public')
                            ->maxSize(10240) // 10MB
                            ->getUploadedFileNameForStorageUsing(
                                fn (TemporaryUploadedFile $file, Get $get): string =>
                                Str::slug($get('title') ?? 'sermon-notes') . '-notes.' . $file->getClientOriginalExtension()
                            )
                            ->helperText('Upload a PDF file for downloadable sermon notes')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('sermon_notes_content')
                            ->label('Sermon Notes Content (SEO Optimized)')
                            ->helperText('Add typeable sermon notes content for better SEO visibility and search indexing')
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'codeBlock',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->columnSpanFull(),

                        Forms\Components\Select::make('sermon_notes_content_type')
                            ->label('Content Type')
                            ->options([
                                'rich_text' => 'Rich Text (HTML)',
                                'markdown' => 'Markdown',
                                'plain_text' => 'Plain Text',
                            ])
                            ->default('rich_text')
                            ->helperText('Choose how the sermon notes content should be formatted'),                                        Forms\Components\TextInput::make('duration_minutes')
                                            ->label('Duration (minutes)')
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(300)
                                            ->placeholder('45'),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make('Organization & Publishing')
                            ->schema([
                                Section::make('Categorization')
                                    ->schema([
                                        Forms\Components\TagsInput::make('tags')
                                            ->label('Tags')
                                            ->placeholder('Add tags...')
                                            ->columnSpanFull(),

                                        Forms\Components\DatePicker::make('series_date')
                                            ->label('Series Date')
                                            ->required()
                                            ->default(now()),

                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Lower numbers appear first'),
                                    ])
                                    ->columns(2),

                                Section::make('Publishing Options')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_published')
                                            ->label('Published')
                                            ->default(true)
                                            ->helperText('Make this series visible to the public'),

                                        Forms\Components\Toggle::make('is_featured')
                                            ->label('Featured')
                                            ->default(false)
                                            ->helperText('Show this series prominently on the homepage'),

                                        Forms\Components\TextInput::make('views_count')
                                            ->label('Views Count')
                                            ->numeric()
                                            ->default(0)
                                            ->disabled()
                                            ->helperText('Auto-updated when users view the series'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(60),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('pastor')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('series_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('sermon_notes')
                    ->label('PDF Notes')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('info')
                    ->getStateUsing(fn ($record) => !empty($record->sermon_notes)),

                Tables\Columns\IconColumn::make('sermon_notes_content')
                    ->label('SEO Content')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-magnifying-glass')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->getStateUsing(fn ($record) => !empty($record->sermon_notes_content)),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-minus'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'Sermons' => 'Sermons',
                        'Bible Study' => 'Bible Study',
                        'Devotional' => 'Devotional',
                        'Worship' => 'Worship',
                        'Youth' => 'Youth',
                        'Family' => 'Family',
                        'Evangelism' => 'Evangelism',
                        'Prayer' => 'Prayer',
                        'Discipleship' => 'Discipleship',
                    ])
                    ->multiple(),

                SelectFilter::make('pastor')
                    ->options([
                        'Pastor John Smith' => 'Pastor John Smith',
                        'Pastor Mary Johnson' => 'Pastor Mary Johnson',
                        'Pastor David Wilson' => 'Pastor David Wilson',
                        'Guest Speaker' => 'Guest Speaker',
                    ])
                    ->multiple(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published Status'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('sendNote')
                    ->label('Send Note')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->tooltip('Send sermon notes to individuals or church members')
                    ->form([
                        Forms\Components\Select::make('email_type')
                            ->label('Email Type')
                            ->options([
                                'template' => 'Use Default Template',
                                'custom' => 'Compose Custom Email',
                            ])
                            ->required()
                            ->default('template')
                            ->live()
                            ->reactive(),

                        Forms\Components\Select::make('send_type')
                            ->label('Send To')
                            ->options([
                                'individual' => 'Specific Email Address',
                                'all_members' => 'All Church Members',
                                'active_members' => 'Active Members Only',
                                'full_members' => 'Full Members Only',
                                'regular_attendees' => 'Regular Attendees',
                                'visitors' => 'Visitors',
                            ])
                            ->required()
                            ->default('individual')
                            ->live()
                            ->reactive(),

                        Forms\Components\TextInput::make('recipient_email')
                            ->label('Recipient Email')
                            ->email()
                            ->required()
                            ->placeholder('Enter email address')
                            ->visible(fn (Forms\Get $get): bool => $get('send_type') === 'individual'),

                        Forms\Components\TextInput::make('recipient_name')
                            ->label('Recipient Name (Optional)')
                            ->placeholder('Enter recipient name')
                            ->visible(fn (Forms\Get $get): bool => $get('send_type') === 'individual'),

                        Forms\Components\Placeholder::make('member_count')
                            ->label('Recipients')
                            ->content(function (Forms\Get $get): string {
                                $sendType = $get('send_type');
                                if (!$sendType || $sendType === 'individual') {
                                    return 'Enter email address above';
                                }

                                $count = match($sendType) {
                                    'all_members' => Member::active()->count(),
                                    'active_members' => Member::active()->count(),
                                    'full_members' => Member::active()->members()->count(),
                                    'regular_attendees' => Member::active()->regularAttendees()->count(),
                                    'visitors' => Member::active()->visitors()->count(),
                                    default => 0
                                };

                                return "This will send to {$count} recipients";
                            })
                            ->visible(fn (Forms\Get $get): bool => $get('send_type') !== 'individual'),

                        // Custom email composition fields
                        Forms\Components\TextInput::make('custom_subject')
                            ->label('Email Subject')
                            ->required()
                            ->placeholder('Enter email subject')
                            ->default(fn ($record) => 'Sermon Notes: ' . $record->title)
                            ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),

                        Forms\Components\RichEditor::make('custom_message')
                            ->label('Email Message')
                            ->required()
                            ->placeholder('Compose your email message here...')
                            ->default(function ($record) {
                                $defaultMessage = "Hello church family,\n\n";
                                $defaultMessage .= "I hope this message finds you well. I'm delighted to share the sermon notes from our recent teaching series with you.\n\n";
                                $defaultMessage .= "**Series:** " . $record->title . "\n";
                                if ($record->pastor) {
                                    $defaultMessage .= "**Speaker:** " . $record->pastor . "\n";
                                }
                                if ($record->series_date) {
                                    $defaultMessage .= "**Date:** " . $record->series_date->format('F j, Y') . "\n";
                                }
                                if ($record->scripture_references) {
                                    $defaultMessage .= "**Scripture References:** " . $record->scripture_references . "\n";
                                }
                                $defaultMessage .= "\nPlease find the complete sermon notes attached to this email. These notes include key points, scripture references, and practical applications from the message.\n\n";
                                if ($record->video_url) {
                                    $defaultMessage .= "You can also watch the full message online at: " . $record->video_url . "\n\n";
                                }
                                $defaultMessage .= "May God bless you as you continue to grow in your faith journey.\n\n";
                                $defaultMessage .= "Blessings,\n[Your Name]";

                                return $defaultMessage;
                            })
                            ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),

                        Forms\Components\Toggle::make('attach_sermon_notes')
                            ->label('Attach Sermon Notes PDF')
                            ->default(true)
                            ->helperText('Include the sermon notes PDF as an attachment')
                            ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),
                    ])
                    ->action(function (array $data, TeachingSeries $record): void {
                        if (!$record->sermon_notes) {
                            Notification::make()
                                ->title('No sermon notes available')
                                ->body('This teaching series does not have sermon notes attached.')
                                ->warning()
                                ->send();
                            return;
                        }

                        try {
                            $successCount = 0;
                            $errorCount = 0;

                            if ($data['send_type'] === 'individual') {
                                // Send to single email
                                if ($data['email_type'] === 'custom') {
                                    // Use custom email
                                    Mail::send(new CustomSermonNoteMail(
                                        $record,
                                        $data['recipient_email'],
                                        $data['recipient_name'] ?? '',
                                        $data['custom_subject'],
                                        $data['custom_message'],
                                        $data['attach_sermon_notes'] ?? true
                                    ));
                                } else {
                                    // Use template email
                                    Mail::send(new SermonNoteMail(
                                        $record,
                                        $data['recipient_email'],
                                        $data['recipient_name'] ?? ''
                                    ));
                                }
                                $successCount = 1;

                                Notification::make()
                                    ->title('Sermon notes sent successfully!')
                                    ->body('The sermon notes have been sent to ' . $data['recipient_email'])
                                    ->success()
                                    ->send();
                            } else {
                                // Send to multiple members based on type
                                $members = match($data['send_type']) {
                                    'all_members' => Member::active()->get(),
                                    'active_members' => Member::active()->get(),
                                    'full_members' => Member::active()->members()->get(),
                                    'regular_attendees' => Member::active()->regularAttendees()->get(),
                                    'visitors' => Member::active()->visitors()->get(),
                                    default => collect()
                                };

                                foreach ($members as $member) {
                                    if ($member->email) {
                                        try {
                                            if ($data['email_type'] === 'custom') {
                                                // Use custom email
                                                Mail::send(new CustomSermonNoteMail(
                                                    $record,
                                                    $member->email,
                                                    $member->full_name ?? $member->first_name ?? '',
                                                    $data['custom_subject'],
                                                    $data['custom_message'],
                                                    $data['attach_sermon_notes'] ?? true
                                                ));
                                            } else {
                                                // Use template email
                                                Mail::send(new SermonNoteMail(
                                                    $record,
                                                    $member->email,
                                                    $member->full_name ?? $member->first_name ?? ''
                                                ));
                                            }
                                            $successCount++;
                                        } catch (\Exception $e) {
                                            $errorCount++;
                                        }
                                    }
                                }

                                $message = "Sent sermon notes to {$successCount} recipients successfully.";
                                if ($errorCount > 0) {
                                    $message .= " {$errorCount} failed to send.";
                                }

                                Notification::make()
                                    ->title('Bulk sending completed')
                                    ->body($message)
                                    ->success()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Failed to send sermon notes')
                                ->body('There was an error sending the email: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (TeachingSeries $record): bool => !empty($record->sermon_notes)),
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('sendNotesToMembers')
                        ->label('Send Notes to Church Members')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->form([
                            Forms\Components\Select::make('member_type')
                                ->label('Send To')
                                ->options([
                                    'all_members' => 'All Church Members',
                                    'active_members' => 'Active Members Only',
                                    'full_members' => 'Full Members Only',
                                    'regular_attendees' => 'Regular Attendees',
                                    'visitors' => 'Visitors',
                                ])
                                ->required()
                                ->default('all_members')
                                ->live(),

                            Forms\Components\Placeholder::make('member_count')
                                ->label('Recipients')
                                ->content(function (Forms\Get $get): string {
                                    $memberType = $get('member_type');
                                    if (!$memberType) {
                                        return 'Select member type above';
                                    }

                                    $count = match($memberType) {
                                        'all_members' => Member::active()->count(),
                                        'active_members' => Member::active()->count(),
                                        'full_members' => Member::active()->members()->count(),
                                        'regular_attendees' => Member::active()->regularAttendees()->count(),
                                        'visitors' => Member::active()->visitors()->count(),
                                        default => 0
                                    };

                                    return "This will send to {$count} recipients";
                                }),

                            Forms\Components\Select::make('email_type')
                                ->label('Email Type')
                                ->options([
                                    'template' => 'Use Standard Template',
                                    'custom' => 'Compose Custom Message'
                                ])
                                ->required()
                                ->default('template')
                                ->live()
                                ->afterStateUpdated(fn ($state, Forms\Set $set) => $state === 'template' ?
                                    $set('custom_subject', null) : null),

                            Forms\Components\TextInput::make('custom_subject')
                                ->label('Subject')
                                ->required()
                                ->placeholder('Enter email subject')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),

                            Forms\Components\Textarea::make('custom_message')
                                ->label('Message')
                                ->rows(10)
                                ->required()
                                ->placeholder('Compose your email message here...')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),

                            Forms\Components\Toggle::make('attach_sermon_notes')
                                ->label('Attach Sermon Notes PDF')
                                ->default(true)
                                ->helperText('Include the sermon notes PDF as an attachment')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),
                        ])
                        ->action(function (array $data, $records): void {
                            // Get members based on type
                            $members = match($data['member_type']) {
                                'all_members' => Member::active()->get(),
                                'active_members' => Member::active()->get(),
                                'full_members' => Member::active()->members()->get(),
                                'regular_attendees' => Member::active()->regularAttendees()->get(),
                                'visitors' => Member::active()->visitors()->get(),
                                default => collect()
                            };

                            $successCount = 0;
                            $errorCount = 0;
                            $noNotesCount = 0;

                            foreach ($records as $record) {
                                if (!$record->sermon_notes) {
                                    $noNotesCount++;
                                    continue;
                                }

                                foreach ($members as $member) {
                                    if ($member->email) {
                                        try {
                                            if ($data['email_type'] === 'custom') {
                                                // Use custom email
                                                Mail::send(new CustomSermonNoteMail(
                                                    $record,
                                                    $member->email,
                                                    $member->full_name ?? $member->first_name ?? '',
                                                    $data['custom_subject'],
                                                    $data['custom_message'],
                                                    $data['attach_sermon_notes'] ?? true
                                                ));
                                            } else {
                                                // Use template email
                                                Mail::send(new SermonNoteMail(
                                                    $record,
                                                    $member->email,
                                                    $member->full_name ?? $member->first_name ?? ''
                                                ));
                                            }
                                            $successCount++;
                                        } catch (\Exception $e) {
                                            $errorCount++;
                                        }
                                    }
                                }
                            }

                            $message = "Sent {$successCount} emails successfully.";
                            if ($errorCount > 0) {
                                $message .= " {$errorCount} failed to send.";
                            }
                            if ($noNotesCount > 0) {
                                $message .= " {$noNotesCount} series had no notes to send.";
                            }

                            Notification::make()
                                ->title('Bulk email sending completed')
                                ->body($message)
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('sendNotesToCustomEmails')
                        ->label('Send Notes to Custom Emails')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->form([
                            Forms\Components\Textarea::make('recipient_emails')
                                ->label('Recipient Emails')
                                ->placeholder('Enter email addresses separated by commas or new lines')
                                ->rows(4)
                                ->required()
                                ->helperText('Enter multiple email addresses separated by commas or new lines'),

                            Forms\Components\Select::make('email_type')
                                ->label('Email Type')
                                ->options([
                                    'template' => 'Use Standard Template',
                                    'custom' => 'Compose Custom Message'
                                ])
                                ->required()
                                ->default('template')
                                ->live(),

                            Forms\Components\TextInput::make('sender_name')
                                ->label('Your Name (Optional)')
                                ->placeholder('Your name for personalization')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'template'),

                            Forms\Components\TextInput::make('custom_subject')
                                ->label('Subject')
                                ->required()
                                ->placeholder('Enter email subject')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),

                            Forms\Components\Textarea::make('custom_message')
                                ->label('Message')
                                ->rows(8)
                                ->required()
                                ->placeholder('Compose your email message here...')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),

                            Forms\Components\Toggle::make('attach_sermon_notes')
                                ->label('Attach Sermon Notes PDF')
                                ->default(true)
                                ->helperText('Include the sermon notes PDF as an attachment')
                                ->visible(fn (Forms\Get $get): bool => $get('email_type') === 'custom'),
                        ])
                        ->action(function (array $data, $records): void {
                            $emailString = $data['recipient_emails'];
                            // Split by comma or newline and clean up
                            $emails = preg_split('/[,\n\r]+/', $emailString);
                            $emails = array_map('trim', $emails);
                            $emails = array_filter($emails, function($email) {
                                return filter_var($email, FILTER_VALIDATE_EMAIL);
                            });

                            if (empty($emails)) {
                                Notification::make()
                                    ->title('No valid email addresses')
                                    ->body('Please enter valid email addresses.')
                                    ->warning()
                                    ->send();
                                return;
                            }

                            $successCount = 0;
                            $errorCount = 0;
                            $noNotesCount = 0;

                            foreach ($records as $record) {
                                if (!$record->sermon_notes) {
                                    $noNotesCount++;
                                    continue;
                                }

                                foreach ($emails as $email) {
                                    try {
                                        if ($data['email_type'] === 'custom') {
                                            // Use custom email
                                            Mail::send(new CustomSermonNoteMail(
                                                $record,
                                                $email,
                                                '', // No name for custom emails
                                                $data['custom_subject'],
                                                $data['custom_message'],
                                                $data['attach_sermon_notes'] ?? true
                                            ));
                                        } else {
                                            // Use template email
                                            Mail::send(new SermonNoteMail(
                                                $record,
                                                $email,
                                                $data['sender_name'] ?? ''
                                            ));
                                        }
                                        $successCount++;
                                    } catch (\Exception $e) {
                                        $errorCount++;
                                    }
                                }
                            }

                            $message = "Sent {$successCount} emails successfully.";
                            if ($errorCount > 0) {
                                $message .= " {$errorCount} failed to send.";
                            }
                            if ($noNotesCount > 0) {
                                $message .= " {$noNotesCount} series had no notes to send.";
                            }

                            Notification::make()
                                ->title('Bulk email sending completed')
                                ->body($message)
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each->update(['is_published' => true]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn ($records) => $records->each->update(['is_published' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('series_date', 'desc');
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
            'index' => Pages\ListTeachingSeries::route('/'),
            'create' => Pages\CreateTeachingSeries::route('/create'),
            'edit' => Pages\EditTeachingSeries::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}
