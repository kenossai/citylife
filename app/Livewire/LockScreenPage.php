<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component as LivewireComponent;

class LockScreenPage extends LivewireComponent implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public ?User $lockedUser = null;

    public function mount(): void
    {
        // Check if there's a lock screen session or locked user ID
        if (!session()->has('lock_screen') && !session()->has('locked_user_id')) {
            $this->redirect(filament()->getLoginUrl());
            return;
        }

        // Get the locked user (might not be authenticated if session expired)
        if (Auth::check()) {
            $this->lockedUser = Auth::user();
        } elseif (session()->has('locked_user_id')) {
            $this->lockedUser = User::find(session('locked_user_id'));
        }

        if (!$this->lockedUser) {
            // No user found, redirect to login
            session()->flush();
            $this->redirect(filament()->getLoginUrl());
            return;
        }

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->autocomplete('current-password')
                    ->autofocus()
                    ->extraInputAttributes(['tabindex' => 1]),
            ])
            ->statePath('data');
    }

    public function unlock(): void
    {
        $data = $this->form->getState();

        if (!$this->lockedUser || !Hash::check($data['password'], $this->lockedUser->password)) {
            Notification::make()
                ->title('Invalid password')
                ->danger()
                ->send();

            $this->data['password'] = '';
            return;
        }

        // Re-authenticate the user if they were logged out
        if (!Auth::check()) {
            Auth::login($this->lockedUser);
        }

        // Clear lock screen flags
        session()->forget(['lock_screen', 'locked_user_id', 'locked_user_email']);
        session()->regenerate();
        session(['last_activity_time' => time()]);

        Notification::make()
            ->title('Welcome back!')
            ->success()
            ->send();

        $this->redirect(filament()->getUrl());
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(filament()->getLoginUrl());
    }

    public function render()
    {
        return view('livewire.lock-screen-page');
    }
}
