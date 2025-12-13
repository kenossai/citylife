<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseEnrollmentResource\Pages;
use App\Filament\Resources\CourseEnrollmentResource\RelationManagers;
use App\Filament\Resources\AttendanceResource;
use App\Models\CourseEnrollment;
use App\Models\Course;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseEnrollmentResource extends Resource
{
    protected static ?string $model = CourseEnrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Education & Training';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->label('Course')
                    ->options(Course::pluck('title', 'id'))
                    ->searchable()
                    ->required()
                    ->disabled(fn ($record) => $record !== null), // Disable editing existing enrollments
                Forms\Components\Select::make('user_id')
                    ->label('Member')
                    ->options(Member::selectRaw("id, CONCAT(first_name, ' ', last_name, ' (', email, ')') as full_name")
                        ->pluck('full_name', 'id'))
                    ->searchable()
                    ->required()
                    ->disabled(fn ($record) => $record !== null), // Disable editing existing enrollments
                Forms\Components\DatePicker::make('enrollment_date')
                    ->required()
                    ->default(now())
                    ->disabled(fn ($record) => $record !== null), // Disable editing existing enrollments
                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'dropped' => 'Dropped',
                        'suspended' => 'Suspended',
                    ])
                    ->required()
                    ->default('active'),
                Forms\Components\TextInput::make('progress_percentage')
                    ->label('Progress (%)')
                    ->numeric()
                    ->default(0.00)
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01)
                    ->disabled()
                    ->helperText('Auto-calculated based on attendance'),
                Forms\Components\TextInput::make('completed_lessons')
                    ->label('Completed Lessons')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->disabled()
                    ->helperText('Auto-calculated based on attendance'),
                Forms\Components\TextInput::make('overall_grade')
                    ->label('Overall Grade')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->disabled()
                    ->helperText('Auto-calculated based on quiz scores'),
                Forms\Components\DatePicker::make('completion_date')
                    ->label('Completion Date')
                    ->disabled()
                    ->helperText('Auto-set when course is completed'),
                Forms\Components\Toggle::make('certificate_issued')
                    ->label('Certificate Issued')
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function ($state, $set, $record) {
                        if ($state) {
                            $set('certificate_issued_at', now()->format('Y-m-d H:i:s'));
                            $currentUser = filament()->auth()->user();
                            $set('issued_by', $currentUser->name ?? 'Admin');
                        } else {
                            $set('certificate_issued_at', null);
                            $set('issued_by', null);
                        }
                    }),
                Forms\Components\TextInput::make('certificate_number')
                    ->label('Certificate Number')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('certificate_issued')),
                Forms\Components\FileUpload::make('certificate_file_path')
                    ->label('Certificate File')
                    ->disk('s3')
                    ->visibility('public')
                    ->directory('certificates')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->maxSize(10240) // 10MB
                    ->visible(fn ($get) => $get('certificate_issued'))
                    ->helperText('Upload the certificate file (PDF or image, max 10MB)'),
                Forms\Components\DateTimePicker::make('certificate_issued_at')
                    ->label('Certificate Issued At')
                    ->visible(fn ($get) => $get('certificate_issued'))
                    ->disabled(),
                Forms\Components\TextInput::make('issued_by')
                    ->label('Issued By')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('certificate_issued'))
                    ->disabled(),
                Forms\Components\TextInput::make('payment_info')
                    ->disabled(fn ($record) => $record !== null)
                    ->helperText('Payment info can only be set during enrollment creation'),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Member')
                    ->getStateUsing(function ($record) {
                        $user = $record->user;
                        return $user ? $user->first_name . ' ' . $user->last_name : 'N/A';
                    })
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enrollment_date')
                    ->label('Enrolled')
                    ->date()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'primary' => 'completed',
                        'warning' => 'suspended',
                        'danger' => 'dropped',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_lessons')
                    ->label('Lessons Done')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('overall_grade')
                    ->label('Grade')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : '-')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completion_date')
                    ->label('Completed')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('certificate_issued')
                    ->label('Certificate')
                    ->boolean(),
                Tables\Columns\TextColumn::make('certificate_number')
                    ->label('Cert. Number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('certificate_file_path')
                    ->label('Certificate File')
                    ->formatStateUsing(fn ($state) => $state ? 'Uploaded' : 'Not uploaded')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('attendance_summary')
                    ->label('Attendance')
                    ->getStateUsing(function ($record) {
                        $totalAttendance = $record->attendance()->count();
                        $attendedCount = $record->attendance()->where('attended', true)->count();
                        return $totalAttendance > 0 ? "{$attendedCount}/{$totalAttendance}" : 'Not tracked';
                    })
                    ->badge()
                    ->color(function ($state) {
                        if ($state === 'Not tracked') return 'gray';
                        [$attended, $total] = explode('/', $state);
                        $percentage = $total > 0 ? ($attended / $total) * 100 : 0;
                        return $percentage >= 80 ? 'success' : ($percentage >= 60 ? 'warning' : 'danger');
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Student')
                    ->options(function () {
                        return Member::selectRaw("id, CONCAT(first_name, ' ', last_name) as full_name")
                            ->pluck('full_name', 'id');
                    })
                    ->searchable(),

                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'title')
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'dropped' => 'Dropped',
                        'suspended' => 'Suspended',
                    ]),

                Tables\Filters\Filter::make('certificate_issued')
                    ->label('Certificate Issued')
                    ->query(fn (Builder $query): Builder => $query->where('certificate_issued', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('mark_attendance')
                    ->label('Mark Attendance')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->url(fn ($record) => AttendanceResource::getUrl('create', [
                        'course_enrollment_id' => $record->id
                    ]))
                    ->openUrlInNewTab(false),
                Tables\Actions\Action::make('recalculate_progress')
                    ->label('Recalc Progress')
                    ->icon('heroicon-o-calculator')
                    ->color('info')
                    ->action(function ($record) {
                        $record->updateProgressFromAttendance();
                        \Filament\Notifications\Notification::make()
                            ->title('Progress Updated')
                            ->body('Enrollment progress has been recalculated based on attendance.')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Recalculate Progress')
                    ->modalDescription('This will update the progress percentage and completed lessons based on current attendance records.')
                    ->modalSubmitActionLabel('Recalculate'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('recalculate_progress_bulk')
                        ->label('Recalculate Progress')
                        ->icon('heroicon-o-calculator')
                        ->color('info')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                $record->updateProgressFromAttendance();
                            }
                            \Filament\Notifications\Notification::make()
                                ->title('Progress Updated')
                                ->body("Progress recalculated for {$records->count()} enrollments.")
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Recalculate Progress for Selected')
                        ->modalDescription('This will update progress for all selected enrollments based on their attendance records.')
                        ->modalSubmitActionLabel('Recalculate All'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Removed AttendanceRelationManager - now handled by dedicated AttendanceResource
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseEnrollments::route('/'),
            'create' => Pages\CreateCourseEnrollment::route('/create'),
            'edit' => Pages\EditCourseEnrollment::route('/{record}/edit'),
        ];
    }
}
