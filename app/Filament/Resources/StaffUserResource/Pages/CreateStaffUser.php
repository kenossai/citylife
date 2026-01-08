<?php

namespace App\Filament\Resources\StaffUserResource\Pages;

use App\Filament\Resources\StaffUserResource;
use App\Mail\StaffWelcomeMail;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CreateStaffUser extends CreateRecord
{
    protected static string $resource = StaffUserResource::class;

    protected ?string $temporaryPassword = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If no password was provided, generate a temporary one
        if (empty($data['password'])) {
            $this->temporaryPassword = Str::random(12);
            $data['password'] = bcrypt($this->temporaryPassword);
            $data['force_password_change'] = true;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Generate a signed URL for secure login (valid for 24 hours)
        $loginUrl = URL::temporarySignedRoute(
            'filament.admin.auth.login',
            now()->addHours(24),
            ['email' => encrypt($this->record->email)]
        );

        // Send welcome email to the newly created staff user
        Mail::to($this->record->email)->send(
            new StaffWelcomeMail($this->record, $this->temporaryPassword, $loginUrl)
        );
    }
}
